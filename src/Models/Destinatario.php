<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\VeriFactuStringHelper;

class Destinatario
{
    /**
     * Nombre-razón social del destinatario (a veces también denominado contraparte, es decir, el cliente) de la operación.
     */
    public string $NombreRazon;
    /**
     * Identificador del NIF del destinatario (a veces también denominado contraparte, es decir, el cliente) de la operación.
     */
    public ?string $NIF = null;
    public ?IDOtro $IDOtro = null;

    public function __construct(?string $nif, ?string $nombreRazon=null, ?IDOtro $idOtro=null)
    {
        $this->NIF = $nif;
        $this->NombreRazon = $nombreRazon;
        $this->IDOtro = $idOtro;
    }

    public function toArray() {
        $data = [
            'NombreRazon' => VeriFactuStringHelper::sanitizeString($this->NombreRazon)
        ];
        if (($this->NIF==null && $this->IDOtro==null) || ($this->NIF!=null && $this->IDOtro!=null)) {
            throw new \Exception('Destinatario requiere NIF ó IDOtro.');
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