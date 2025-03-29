<?php
namespace jdg\Verifactu\Models;

class IDFacturaRectificada
{
    /**
     * NIF del obligado a expedir la factura.
     */
    public string $IDEmisorFactura;
    /**
     * Nº Serie+Nº Factura que identifica a la factura emitida.
     */
    public string $NumSerieFactura;
    /**
     * Fecha de expedición de la factura.
     */
    public string $FechaExpedicionFactura;

    public function toArray() {
        return [
            'IDEmisorFactura' => $this->IDEmisorFactura,
            'NumSerieFactura' => $this->NumSerieFactura,
            'FechaExpedicionFactura' => $this->FechaExpedicionFactura
        ];
    }
}