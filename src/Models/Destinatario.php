<?php
namespace jdg\Verifactu\Models;

class Destinatario
{
    public string $NombreRazon;
    public string $NIF;

    public function __construct(string $nif, string $nombreRazon)
    {
        $this->NIF = $nif;
        $this->NombreRazon = $nombreRazon;
    }
}