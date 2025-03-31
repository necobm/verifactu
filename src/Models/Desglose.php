<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\Listas;

class Desglose
{
    /**
     * Impuesto de aplicación. Si no se informa este campo se entenderá que el impuesto de aplicación es el IVA. Este campo es necesario porque contribuye a completar el detalle de la tipología de la factura.
     */
    public ?Listas\L1 $Impuesto = null;
    /**
     * Clave que identificará el tipo de régimen del impuesto o una operación con trascendencia tributaria.
     */
    public ?Listas\L8A $ClaveRegimen = null;
    /**
     * Clave de la operación sujeta y no exenta o de la operación no sujeta.
     */
    public ?Listas\L9 $CalificacionOperacion = null;
    /**
     * Campo que especifica la causa de exención.
     */
    public ?Listas\L10 $OperacionExenta = null;
    /**
     * Porcentaje aplicado sobre la base imponible para calcular la cuota.
     */
    public ?string $TipoImpositivo = null;
    /**
     * Magnitud dineraria sobre la que se aplica el tipo impositivo / Importe no sujeto.
     */
    public string $BaseImponibleOimporteNoSujeto;
    /**
     * Magnitud dineraria sobre la que se aplica el tipo impositivo en régimen especial de grupos nivel avanzado.
     */
    public ?string $BaseImponibleACoste = null;
    /**
     * Cuota resultante de aplicar a la base imponible el tipo impositivo.
     */
    public ?string $CuotaRepercutida = null;
    /**
     * Porcentaje asociado en función del impuesto y tipo impositivo
     */
    public ?string $TipoRecargoEquivalencia = null;
    /**
     * Cuota resultante de aplicar a la base imponible el tipo de recargo de equivalencia.
     */
    public ?string $CuotaRecargoEquivalencia = null;

    public function toArray() {
        $data = [];
        if ($this->Impuesto!=null) {
            $data['Impuesto'] = $this->Impuesto->value;
        }
        if ($this->ClaveRegimen!=null) {
            $data['ClaveRegimen'] = $this->ClaveRegimen->value;
        }
        if (($this->CalificacionOperacion==null && $this->OperacionExenta==null) || ($this->CalificacionOperacion!=null && $this->OperacionExenta!=null)) {
            throw new \Exception('Desglose requiere CalificacionOperacion ú OperacionExenta.');
        }
        if ($this->CalificacionOperacion!=null) {
            $data['CalificacionOperacion'] = $this->CalificacionOperacion->value;
        } else {
            $data['OperacionExenta'] = $this->OperacionExenta->value;
        }
        if ($this->TipoImpositivo!=null) {
            $data['TipoImpositivo'] = bcadd('0', $this->TipoImpositivo, 2);
        }
        $data['BaseImponibleOimporteNoSujeto'] = bcadd('0', $this->BaseImponibleOimporteNoSujeto, 2);
        if ($this->BaseImponibleACoste!=null) {
            $data['BaseImponibleACoste'] = bcadd('0', $this->BaseImponibleACoste, 2);
        }
        if ($this->CuotaRepercutida!=null) {
            $data['CuotaRepercutida'] = bcadd('0', $this->CuotaRepercutida, 2);
        }
        if ($this->TipoRecargoEquivalencia!=null) {
            $data['TipoRecargoEquivalencia'] = bcadd('0', $this->TipoRecargoEquivalencia, 2);
        }
        if ($this->CuotaRecargoEquivalencia!=null) {
            $data['CuotaRecargoEquivalencia'] = bcadd('0', $this->CuotaRecargoEquivalencia, 2);
        }
        return $data;
    }
}