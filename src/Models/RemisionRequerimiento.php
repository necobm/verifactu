<?php
namespace jdg\Verifactu\Models;

class RemisionRequerimiento
{
    /**
     * Sólo cuando el motivo de la remisión sea para dar respuesta a un requerimiento de información previo 
     */
    public string $RefRequerimiento;
    /**
     * Indicador que especifica que se ha finalizado la remisión de registros de facturación tras un requerimiento, 
     */
    public string $FinRequerimiento;
}