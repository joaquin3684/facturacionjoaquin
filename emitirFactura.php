<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 13/04/19
 * Time: 04:45
 */
define ("WSDL_WSFE", "wsfe.wsdl");     # The WSDL corresponding to WSAA
define ("URL", "https://wswhomo.afip.gov.ar/wsfe/service.asmx");

$client_wsfe = new SoapClient( WSDL_WSFE , array(

    'soap_version'   => SOAP_1_2,
    'location'       => URL,
    'trace'          => 1,
    'exceptions'     => 1
) );

$results_AutRequest = $client_wsfe->FECAESolicitar(
    array(
        'Auth' => array
        (
            'Token' => "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/Pgo8c3NvIHZlcnNpb249IjIuMCI+CiAgICA8aWQgc3JjPSJDTj13c2FhaG9tbywgTz1BRklQLCBDPUFSLCBTRVJJQUxOVU1CRVI9Q1VJVCAzMzY5MzQ1MDIzOSIgZHN0PSJDTj13c2ZlLCBPPUFGSVAsIEM9QVIiIHVuaXF1ZV9pZD0iNDA5MTk2NTg3NyIgZ2VuX3RpbWU9IjE1NTUxMTkyMzEiIGV4cF90aW1lPSIxNTU1MTYyNDkxIi8+CiAgICA8b3BlcmF0aW9uIHR5cGU9ImxvZ2luIiB2YWx1ZT0iZ3JhbnRlZCI+CiAgICAgICAgPGxvZ2luIGVudGl0eT0iMzM2OTM0NTAyMzkiIHNlcnZpY2U9IndzZmUiIHVpZD0iU0VSSUFMTlVNQkVSPUNVSVQgMjAzOTYyNDUyNzMsIENOPWRuam9hcXVpbiIgYXV0aG1ldGhvZD0iY21zIiByZWdtZXRob2Q9IjIyIj4KICAgICAgICAgICAgPHJlbGF0aW9ucz4KICAgICAgICAgICAgICAgIDxyZWxhdGlvbiBrZXk9IjIwMzk2MjQ1MjczIiByZWx0eXBlPSI0Ii8+CiAgICAgICAgICAgIDwvcmVsYXRpb25zPgogICAgICAgIDwvbG9naW4+CiAgICA8L29wZXJhdGlvbj4KPC9zc28+Cg==",
            'Sign'  => "bnxa22BofnOI6ZBWP8bYw1goIjmqirOf9TvNZsyPMAsgWs1r+dPlY1SSQz1fQ9PXVtvd3n3O8GgxllTnmVuP9xFLX9Z4alNTIm+iN8TFpl7S9WCVLg9CVP3QiAqP6FJr31gIPMTmqS+8NimtYhCXGK42vDXEtF0fv76yyx9+Lks=",
            'Cuit'  => 20396245273
        ),
        'FeCAEReq' => array
        (
            'FeCabReq' => array
            (
                'CantReg' => 1,
                'PtoVta' => 1,
                'CbteTipo' => 1 //1 factura A - 6 factura B
            ),
            'FeDetReq' => array
            (
                'FECAEDetRequest' => array
                (
                    'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
                    'DocTipo' 	=> 99, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
                    'DocNro' 	=> 0,  // Número de documento del comprador (0 consumidor final)
                    'CbteDesde' 	=> 1,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
                    'CbteHasta' 	=> 1,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
                    'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
                    'ImpTotal' 	=> 121, // Importe total del comprobante
                    'ImpTotConc' 	=> 0,   // Importe neto no gravado
                    'ImpNeto' 	=> 100, // Importe neto gravado
                    'ImpOpEx' 	=> 0,   // Importe exento de IVA
                    'ImpIVA' 	=> 21,  //Importe total de IVA
                    'ImpTrib' 	=> 0,   //Importe total de tributos
                    'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
                    'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)
                    'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                        array(
                            'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles)
                            'BaseImp' 	=> 100, // Base imponible
                            'Importe' 	=> 21 // Importe
                        )
                    ),
                )
            ),

        )

    ));

    file_put_contents( microtime(true)  . '_results_AutRequest.txt',var_export($results_AutRequest,true));
    file_put_contents( microtime(true)  . '_results_AutRequest_soap.txt',var_export($client_wsfe,true));



    // if ( $results_AutRequest->FEAutRequestResult->RError->percode != 0 )
    if ( isset($results_AutRequest->FECAESolicitarResult->Errors) )
    {
        exit("NoCAE: ".var_export($results_AutRequest,true)."\n");

    }
    else
    {


        // $detalle_response = $results_AutRequest->FEAutRequestResult->FedResp->FEDetalleResponse;
        $detalle_response = $results_AutRequest->FECAESolicitarResult->FeDetResp->FECAEDetResponse;
        $resultado = (string)$results_AutRequest->FECAESolicitarResult->FeCabResp->Resultado;
        if ($resultado != 'A')
        {
            exit("NoCAE: ".var_export($results_AutRequest,true)."\n");

        }

        /*$this->XML = htmlspecialchars( $client_wsfe->__getLastResponse() );
        // file_put_contents('results_AutRequest.txt',var_export($this->XML,true));
        if( $detalle_response ){

            // $this->CAE = $detalle_response->cae;
            $this->CAE = $detalle_response->CAE;
            // $this->VTO_CAE = $detalle_response->fecha_vto;
            $this->VTO_CAE = $detalle_response->CAEFchVto;
            $this->NUMERO_COMPROBANTE = str_pad( ($this->LastCBTE + 1), 8, "0", STR_PAD_LEFT ) ;
            $this->NUMERO_COMPROBANTE = str_pad( ($this->LastCBTE + 1), 8, "0", STR_PAD_LEFT ) ;
        }*/

    }
?>