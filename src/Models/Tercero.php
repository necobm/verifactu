<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\VeriFactuStringHelper;

class Tercero
{
    /**
     * Nombre-razón social del tercero que expida la factura.
     */
    public string $NombreRazon;
    /**
     * Identificador del NIF del tercero que expida la factura.
     */
    public ?string $NIF = null;
    public ?IDOtro $IDOtro = null;

    public function toArray() {

        $data = [
            'NombreRazon' => VeriFactuStringHelper::sanitizeString($this->NombreRazon)
        ];
        if (($this->NIF==null && $this->IDOtro==null) || ($this->NIF!=null && $this->IDOtro!=null)) {
            throw new \Exception('Tercero requiere NIF ó IDOtro.');
        }
        if ($this->NIF!=null) {
            $data['NIF'] = $this->NIF;
        } else
        if ($this->IDOtro!=null) {
            $data['IDOtro'] = $this->IDOtro->toArray();
        }
        return $data;
    }
}