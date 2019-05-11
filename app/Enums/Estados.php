<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 19/10/18
 * Time: 14:43
 */

namespace App\Enums;


class Estados
{
    const CREADO = 'Creado';
    const VALIDADO = 'Validado';
    const RECHAZO_VALIDACION = 'Rechazo por validacion';
    const AUDITORIA_APROBADA = 'Auditoria aprobada';
    const AUDITORIA_OBSERVADA = 'Auditoria observada';
    const RECHAZO_AUDITORIA = 'Rechazo por auditoria';
    const VISITA_CREADA = 'Visita creada';
    const VISITA_REPACTADA = 'Visita repactada';
    const VISITA_CONFIRMADA = 'Visita confirmada';
    const PENDIENTE_DOC = 'Pediente de documentacion';
    const RECHAZO_LOGISTICA = 'Rechazo por logistica';
    const PRESENTADA = 'Presentada';
    const PAGADA = 'Pagada';
    const RECHAZO_ADMINISTRACION= 'Rechazo por administracion';
    const RECHAZO_PRESENTACION= 'Rechazo presentacion';

}