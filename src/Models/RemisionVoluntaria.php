<?php
namespace jdg\Verifactu\Models;

class RemisionVoluntaria
{
    /**
     * Última fecha en la que el sistema informático actuará como «VERI*FACTU». Después de la misma, el sistema 
     */
    public string $FechaFinVeriFactu;
    /**
     * Indicador que especifica si la remisión voluntaria de los registros de facturación se ha visto afectada por algún tipo de incidencia técnica (por ej. ausencia de corriente eléctrica, problemas de conexión a Internet, fallo del sistema informático de facturación…). Si no se informa este campo se entenderá que tiene valor “N”. Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación. A rellenar sólo en los casos de remisión voluntaria «VERI*FACTU» cuando haya ocurrido alguna situación de este tipo.
     */
    public string $Incidencia;
}