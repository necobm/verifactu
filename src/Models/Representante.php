<?php
namespace jdgOpenCode\verifactu\Models;

class Representante
{
    /**
     * Nombre-razón social del representante del obligado tributario. A rellenar solo en caso de que los registros de facturación remitidos hayan sido generados por un representante/asesor del obligado tributario. Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación.
     */
    public string $NombreRazon;
    /**
     * NIF del representante del obligado tributario. A rellenar solo en caso de que los registros de facturación remitidos hayan sido generados por un representante/asesor del obligado tributario. Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación.
     */
    public string $NIF;

    public function toArray() {
        return [
            'NombreRazon'=>$this->NombreRazon,
            'NIF'=>$this->NIF
        ];
    }
}