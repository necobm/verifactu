<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\Listas;
use jdgOpenCode\verifactu\VeriFactuStringHelper;

class IDOtro
{
    /**
     * Código del país del destinatario (a veces también denominado contraparte, es decir, el cliente) de la operación de la factura 
     */
    public ?string $CodigoPais = null;
    /**
     * Clave para establecer el tipo de identificación, en el país de residencia, del destinatario (a veces también denominado contraparte, es decir, el cliente) de la operación de la factura expedida.
     */
    public Listas\L7 $IDType;
    /**
     * Número de identificación, en el país de residencia, del destinatario (a veces también denominado contraparte, es decir, el cliente) de la operación de la factura expedida.
     */
    public string $ID;

    public function __construct(Listas\L7 $idType, string $id, ?string $codigoPais = null)
    {
        $this->ID = $id;
        $this->CodigoPais = $codigoPais;
        $this->IDType = $idType;
    }    

    public function toArray() {
        $data = [
            'IDType' => $this->IDType->value,
            'ID' => VeriFactuStringHelper::sanitizeString($this->ID)
        ];
        if ($this->CodigoPais!=null) {
            $data['CodigoPais'] = $this->CodigoPais;
        }
        return $data;
    }
}