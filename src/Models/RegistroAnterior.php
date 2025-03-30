<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\VeriFactuStringHelper;
use jdg\Verifactu\VeriFactuDateTimeHelper;

class RegistroAnterior
{
    public string $IDEmisorFactura;
    public string $NumSerieFactura;
    public \DateTime $FechaExpedicionFactura;
    public string $Huella;

    public function __construct(string $idEmisorFactura, string $numSerieFactura, \DateTime $fechaExpedicionFactura, $huella)
    {
        $this->IDEmisorFactura = $idEmisorFactura;
        $this->NumSerieFactura = $numSerieFactura;
        $this->FechaExpedicionFactura = $fechaExpedicionFactura;
        $this->Huella = $huella;
    }    

    public function toArray() {
        return [
            'IDEmisorFactura' => $this->IDEmisorFactura,
            'NumSerieFactura' => VeriFactuStringHelper::sanitizeString($this->NumSerieFactura),
            'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($this->FechaExpedicionFactura),
            'Huella' => $this->Huella
        ];
    }
}