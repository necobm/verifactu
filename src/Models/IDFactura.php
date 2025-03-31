<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\VeriFactuDateTimeHelper;
use jdgOpenCode\verifactu\VeriFactuStringHelper;

class IDFactura
{
    /**
     * Número de identificación fiscal (NIF) del obligado a expedir la factura.
     */
    public string $IDEmisorFactura;
    /**
     * Nº Serie+Nº Factura que identifica a la factura emitida.
     */
    public string $NumSerieFactura;
    /**
     * Fecha de expedición de la factura.
     */
    public \DateTime $FechaExpedicionFactura;

    public function toArray() {
        return [
            'IDEmisorFactura' => $this->IDEmisorFactura,
            'NumSerieFactura' => VeriFactuStringHelper::sanitizeString($this->NumSerieFactura),
            'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($this->FechaExpedicionFactura)
        ];
    }
}