<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\VeriFactuStringHelper;
use jdgOpenCode\verifactu\Listas;

class RemisionRequerimiento
{
    /**
     * Sólo cuando el motivo de la remisión sea para dar respuesta a un requerimiento de información previo 
     */
    public string $RefRequerimiento;
    /**
     * Indicador que especifica que se ha finalizado la remisión de registros de facturación tras un requerimiento, 
     */
    public Listas\L4 $FinRequerimiento;

    public function toArray() {
        return [
            'RefRequerimiento'=> VeriFactuStringHelper::sanitizeString($this->RefRequerimiento),
            'FinRequerimiento'=>$this->FinRequerimiento->value
        ];
    }
}