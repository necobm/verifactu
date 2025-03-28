<?php
namespace jdg\Verifactu\Models;

class ObligadoEmision
{
    public string $NombreRazon;
    public string $NIF;

    public function __construct(string $nif, string $nombreRazon)
    {
        $this->NombreRazon = $nombreRazon;
        $this->NIF = $nif;
    }
}