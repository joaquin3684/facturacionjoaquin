<?php
public function getCAE(){


    global $ari;

    //seteo las variables.
    $this->CANT_REGISTROS = 1; //$cantidad_registros
    $this->PRESTA_SERV = 0;


    $moneda = new currency_currency($this->MONEDA);
    $this->MONEDA_AFIP = $moneda->get('codigo_afip');

    /////////////////////////////////tipos de documentos/////////////////////////////////////////////
    # 80 - CUIT 86 - CUIL 87 - CDI 89 - LE 90 - LC 91 - CI extranjera 92 - en trámite
    # 93 - Acta nacimiento 95 - CI Bs. As. RNP 96 - DNI  94 - Pasaporte 00 - CI Policía Federal
    # 01 - CI Buenos Aires 07 - CI Mendoza 08 - CI La Rioja 09 - CI Salta 10 - CI San Juan
    # 11 - CI San Luis 12 - CI Santa Fe 13 - CI Santiago del Estero 14 - CI Tucumán 16 - CI Chaco
    # 17 - CI Chubut  18 - CI Formosa 19 - CI Misiones 20 - CI Neuquén
    ////////////////////////////////////////////////////////////////////////////////////////////////

    $this->TIPO_DOC = 80; //cuit
    $this->IMP_TOTAL_CONCEPTOS = 0;
    $this->IMP_LIQUIDADO_RNI = 0.0;
    $this->IMP_OPERACIONES_EXCENTAS = 0.0;


    //se arma el xml del TRA y se lo guarda en un archivo (TRA_DIR)
    $TRA = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?>' .
        '<loginTicketRequest version="1.0">'.
        '</loginTicketRequest>');
    $TRA->addChild( 'header' );
    $TRA->header->addChild( 'uniqueId', date('U') );
    $TRA->header->addChild( 'generationTime', date('c',date('U')-600) );
    $TRA->header->addChild( 'expirationTime', date('c',date('U')+600) );
    $TRA->addChild( 'service', $this->SERVICE );
    $TRA->asXML( $this->TRA_DIR ); //se graba el archivo

    $STATUS = openssl_pkcs7_sign( $this->TRA_DIR, $this->TMP_TRA_DIR,$this->CERT,
        array($this->PRIVATEKEY, $this->PASSPHRASE),
        array(),
        !PKCS7_DETACHED
    );

    if (!$STATUS)
    {
        $this->error()->addError("ERROR_PKCS7",true);
    }

    $inf = fopen( $this->TMP_TRA_DIR, "r");
    $w = 0;
    $CMS = "";

    while(!feof($inf))
    {
        $buffer = fgets($inf);
        if ( $w++ >= 4 ) {
            $CMS.=$buffer;
        }
    }

    fclose($inf);
    unlink( $this->TRA_DIR );
    unlink( $this->TMP_TRA_DIR );

    $client_wsaa = new SoapClient( $this->WSDL_WSAA );

    $results = $client_wsaa->loginCms(array('in0'=>$CMS));

    if (!file_put_contents( $this->TA, $results->loginCmsReturn)){
        $this->error()->addError("NO_WRITE_TA",true);
    }

    if (is_soap_fault($results))
    {
        $this->error()->addError("FALLO WSAA",true);
    }

    $ta_xml = simplexml_load_string($results->loginCmsReturn);
    $this->TOKEN = $ta_xml->credentials->token;
    $this->SIGN = $ta_xml->credentials->sign;

    $this->QTY = 1;
    $this->ULT_NRO = 1;

    $opts = array(
        'ssl' => array('ciphers'=>'RC4-SHA')
    );

    $client_wsfe = new SoapClient( $this->WSDL_WSFE , array(
        'trace' => true,
        'encoding' => 'UTF-8',
        'cache_wsdl' => WSDL_CACHE_BOTH,
        //'ssl_method' => SOAP_SSL_METHOD_SSLv3,
        'stream_context' => stream_context_create($opts),
        "exceptions" => false
    ) );

    $results_CMPRequest = $client_wsfe->FECompUltimoAutorizado(
        array('Auth' =>
            array(
                'Token'    => $this->TOKEN,
                'Sign'     => $this->SIGN,
                'Cuit'     => $this->CUIT_EMISOR
            ),
            'PtoVta'   => $this->PTO_VTA,
            'CbteTipo' => $this->TIPO_CBTE
        )
    );

    file_put_contents( microtime(true)  . '_results_CMPRequest.txt',var_export($results_CMPRequest,true));
    file_put_contents( microtime(true)  . '_results_CMPRequest_soap.txt',var_export($client_wsfe,true));

    if ( !isset($results_CMPRequest->FECompUltimoAutorizadoResult->CbteNro))
    {
        $this->error()->addError( var_export($results_CMPRequest,true),true);
    }

    $this->LastCBTE = $results_CMPRequest->FECompUltimoAutorizadoResult->CbteNro;

    /* $suma = $this->IMP_NETO + $this->IMP_LIQUIDADO;
    if( $suma !=  $this->IMP_TOTAL)
    {
        // $this->IMP_NETO = $this->IMP_TOTAL - $this->IMP_LIQUIDADO;
        $this->IMP_NETO = $this->IMP_TOTAL - $this->IMP_LIQUIDADO;
    } */
    $this->IMP_TOTAL = OOB_numeric::floatize($this->IMP_TOTAL);
    $this->IMP_LIQUIDADO = OOB_numeric::floatize($this->IMP_LIQUIDADO);

    $suma = $this->IMP_TOTAL + $this->IMP_LIQUIDADO;
    if( $suma !=  $this->IMP_TOTAL && $this->TIPO_CBTE != 2 && $this->TIPO_CBTE != 7 )
    {
        // $this->IMP_NETO = $this->IMP_TOTAL - $this->IMP_LIQUIDADO;
        // if($this->TIPO_CBTE != 2 && $this->TIPO_CBTE != 7 )
        // {
        $this->IMP_NETO = $this->IMP_TOTAL - $this->IMP_LIQUIDADO;
        // }
    }

    ///////////////////



    /////////////////////////////////convertir los montos para el caso que venga con otra moneda
    $default_currency = currency_currency::getDefault();
    /*	$venta_currency = false;
        if($this->MONEDA != $default_currency->id())
        {
            $guardar_movimiento_moneda = true;
            $venta_currency = new currency_currency($this->MONEDA);
        }
     */

    // $monto_total_value = OOB_numeric::floatize($this->TOTAL_VALUE);
    $imp_total = OOB_numeric::floatize($this->IMP_TOTAL);
    $imp_total_conceptos = OOB_numeric::floatize($this->IMP_TOTAL_CONCEPTOS);
    $imp_neto = $this->IMP_NETO;
    $imp_operaciones_exentas = $this->IMP_OPERACIONES_EXCENTAS;
    $imp_trib = 0.00;
    $imp_liquidado =  OOB_numeric::floatize($this->IMP_LIQUIDADO);
    $moneda_cotizacion = $this->MONEDA_COTIZACION;


    // file_put_contents ("imp_neto.txt",var_export($suma ,true));
    // file_put_contents ("imp_neto.txt",var_export($imp_neto ,true));
    // file_put_contents ("imp_operaciones_exentas.txt",var_export($imp_operaciones_exentas ,true));
    // file_put_contents ("imp_liquidado.txt",var_export($imp_liquidado ,true));
    // file_put_contents ("imp_total.txt",var_export($imp_total ,true));


    $obj_moneda = new currency_value();
    $obj_moneda->set('currency', currency_currency::getDefault());

    $tipo_cambio = $this->MONEDA_COTIZACION;
    if($this->MONEDA != $default_currency->id())
    {

        // $obj_moneda = new currency_value();
        // $obj_moneda->set('currency', currency_currency::getDefault());

        /* if($list_mov_monedas = $this->MOVIMIENTO->get('movimientos_movimiento_moneda'))
        {
            foreach($list_mov_monedas as $mov_moneda)
            {
                // $tipo_cambio = $mov_moneda->get('tipo_cambio');
                $movimiento_moneda = $mov_moneda;
                $obj_moneda->set('currency', $movimiento_moneda->get('moneda'));
                $tipo_cambio = round(1 / $movimiento_moneda->get('tipo_cambio'),2);

                break;
            }
        } */
        $tipo_cambio = round(1 / $this->MONEDA_COTIZACION,2);

        // $imp_total = 12;
        $imp_total = round($this->IMP_TOTAL / $tipo_cambio,2);
        $imp_total_conceptos = round($this->IMP_TOTAL_CONCEPTOS / $tipo_cambio,2);
        $imp_neto = round($this->IMP_NETO / $tipo_cambio,2);
        $imp_operaciones_exentas = round($this->IMP_OPERACIONES_EXCENTAS / $tipo_cambio,2);
        $imp_liquidado = round($this->IMP_LIQUIDADO / $tipo_cambio,2);





    }

    $suma = $imp_total_conceptos+$imp_neto+$imp_operaciones_exentas +$imp_liquidado;

    // file_put_contents ("imp_total.txt",var_export($imp_total ,true));

    $impuestos_fe = array();
    if($this->IMPUESTOS_FE)
    {

        $obj_iva = new StdClass();
        $obj_iva->AlicIva = array();

        foreach ($this->IMPUESTOS_FE as $impuesto_fe)
        {


            $obj_iva_elemento = new StdClass();
            $obj_iva_elemento->Id = $impuesto_fe['Id'];

            if($this->MONEDA != $default_currency->id())
            {
                $obj_iva_elemento->BaseImp =  round($impuesto_fe['BaseImp'] / $tipo_cambio,2);
                $obj_iva_elemento->Importe =  round($impuesto_fe['Importe'] / $tipo_cambio,2);
            }
            else
            {
                $obj_iva_elemento->BaseImp = round($impuesto_fe['BaseImp'],2);
                $obj_iva_elemento->Importe = round($impuesto_fe['Importe'],2);

            }
            $obj_iva->AlicIva[] = $obj_iva_elemento;
        }
    }

    /* file_put_contents ("obj_iva.txt",var_export($obj_iva,true));
    file_put_contents ("IMPUESTOS_FE.txt",var_export($this->IMPUESTOS_FE,true));
    file_put_contents ("IMPUESTOS.txt",var_export($imp_total,true)); */



    ///////////////////////////////////////////////////////////////////////////////////////////////


    //////////////////
    $results_AutRequest = $client_wsfe->FECAESolicitar(
        array(
            'Auth' => array
            (
                'Token' => $this->TOKEN,
                'Sign'  => $this->SIGN,
                'Cuit'  => $this->CUIT_EMISOR
            ),
            'FeCAEReq' => array
            (
                'FeCabReq' => array
                (
                    'CantReg' => $this->CANT_REGISTROS,
                    'PtoVta' => $this->PTO_VTA,
                    'CbteTipo' => $this->TIPO_CBTE //1 factura A - 6 factura B
                ),
                'FeDetReq' => array
                (
                    'FECAEDetRequest' => array
                    (
                        'Concepto' => 3, // Productos y servicios
                        'DocTipo' => $this->TIPO_DOC , //80 (CUIT)
                        'DocNro' => floatval($this->NUM_DOC),
                        'CbteDesde' => $this->LastCBTE + 1,
                        'CbteHasta' => $this->LastCBTE + 1,
                        'CbteFch' => date('Ymd'),
                        'ImpTotal' => round($imp_total,2),
                        'ImpTotConc' => round($imp_total_conceptos,2),
                        'ImpNeto' => round($imp_neto,2),
                        'ImpOpEx' => round($imp_operaciones_exentas,2),
                        'ImpTrib' => 0.00,
                        'ImpIVA' => round($imp_liquidado,2),
                        'FchServDesde' =>  date('Ymd'),
                        'FchVtoPago' =>  date('Ymd'),
                        'FchServHasta' =>  date('Ymd'),
                        // 'MonId' => 'PES',
                        'MonId' => $this->MONEDA_AFIP,
                        // 'MonCotiz' => 1,
                        'MonCotiz' => $tipo_cambio,
                        'Iva' => $obj_iva
                    )
                ),

            )

        ));

    file_put_contents( microtime(true)  . '_results_AutRequest.txt',var_export($results_AutRequest,true));
    file_put_contents( microtime(true)  . '_results_AutRequest_soap.txt',var_export($client_wsfe,true));



    // if ( $results_AutRequest->FEAutRequestResult->RError->percode != 0 )
    if ( isset($results_AutRequest->FECAESolicitarResult->Errors) )
    {
        $this->error()->addError('NO_CAE',true);
        $this->error()->addError( var_export($results_AutRequest,true),true );
    }
    else
    {


        // $detalle_response = $results_AutRequest->FEAutRequestResult->FedResp->FEDetalleResponse;
        $detalle_response = $results_AutRequest->FECAESolicitarResult->FeDetResp->FECAEDetResponse;
        $resultado = (string)$results_AutRequest->FECAESolicitarResult->FeCabResp->Resultado;
        if ($resultado != 'A')
        {
            $this->error()->addError('NO_CAE',true);
            $this->error()->addError( var_export($results_AutRequest,true),true );
        }

        $this->XML = htmlspecialchars( $client_wsfe->__getLastResponse() );
        // file_put_contents('results_AutRequest.txt',var_export($this->XML,true));
        if( $detalle_response ){

            // $this->CAE = $detalle_response->cae;
            $this->CAE = $detalle_response->CAE;
            // $this->VTO_CAE = $detalle_response->fecha_vto;
            $this->VTO_CAE = $detalle_response->CAEFchVto;
            $this->NUMERO_COMPROBANTE = str_pad( ($this->LastCBTE + 1), 8, "0", STR_PAD_LEFT ) ;
            $this->NUMERO_COMPROBANTE = str_pad( ($this->LastCBTE + 1), 8, "0", STR_PAD_LEFT ) ;
        }

    }