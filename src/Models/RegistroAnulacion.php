<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\Listas;

class RegistroAnulacion
{
    /**
     * Identificación de la versión actual del esquema o estructura de información utilizada para la generación y conservación / remisión de los registros de facturación. Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación.
     */
    public Listas\L15 $IDVersion;
    public IDFactura $IDFactura;

    public Encadenamiento $Encadenamiento;
    public ?string $Huella;
    public ?string $FechaHoraHusoGenRegistro;


    public function toArray() {
        return [];
    } 
}