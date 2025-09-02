<?php
namespace jdgOpenCode\verifactu\Models;

class ImporteRectificacion
{
    public function __construct(string $BaseRectificada, string $CuotaRectificada, ?string $CuotaRecargoRectificado = null)
    {
        $this->BaseRectificada = $BaseRectificada;
        $this->CuotaRectificada = $CuotaRectificada;
        $this->CuotaRecargoRectificado = $CuotaRecargoRectificado;
    }

    /**
     * Base imponible de la factura.
     */
    public string $BaseRectificada;
    /**
     * Cuota repercutida o soportada de la factura.
     */
    public string $CuotaRectificada;
    /**
     * Cuota recargo de equivalencia de la factura.
     */
    public ?string $CuotaRecargoRectificado = null;

    public function toArray() {
        $data = [
            'BaseRectificada' => bcadd('0', $this->BaseRectificada, 2),
            'CuotaRectificada' => bcadd('0', $this->CuotaRectificada, 2)
        ];
        if ($this->CuotaRecargoRectificado!=null && floatval($this->CuotaRecargoRectificado)!=0) {
            $data['CuotaRectificada'] = bcadd('0', $this->CuotaRectificada, 2);
        }
        return $data;
    }
}