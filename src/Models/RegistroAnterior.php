<?php
namespace jdg\Verifactu\Models;

class RegistroAnterior
{
    public string $IDEmisorFactura;
    public string $NumSerieFactura;
    public string $FechaExpedicionFactura;
    public string $Huella;

    public function __construct(string $idEmisorFactura, string $numSerieFactura, string $fechaExpedicionFactura, $huella)
    {
        $this->IDEmisorFactura = $idEmisorFactura;
        $this->NumSerieFactura = $numSerieFactura;
        $this->FechaExpedicionFactura = $fechaExpedicionFactura;
        $this->Huella = $huella;
    }    
}