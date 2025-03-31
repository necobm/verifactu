<?php
namespace jdgOpenCode\verifactu\Models;

class ObligadoEmision
{
    /**
     * Nombre-razón social del obligado a expedir las facturas.
     */
    public string $NombreRazon;
    /**
     * NIF del obligado a expedir las facturas.
     */
    public string $NIF;

    public function __construct(string $nif, string $nombreRazon)
    {
        $this->NombreRazon = $nombreRazon;
        $this->NIF = $nif;
    }

    public function toArray() {
        return [
            'NombreRazon' => $this->NombreRazon,
            'NIF' => $this->NIF
        ];
    }
}