<?php
namespace jdgOpenCode\verifactu\Models;

class Encadenamiento
{
    /**
     * Indicador que especifica que no existe registro de facturación anterior en este sistema informático por tratarse del primer registro de facturación generado en él. En este caso, se informará con el valor "S". Si no se informa este campo se entenderá que no es el primer registro de facturación, en cuyo caso es obligatorio informar los campos de que consta «RegistroAnterior».
     */
    public bool $PrimerRegistro;
    public ?RegistroAnterior $RegistroAnterior;
    
    public function __construct(RegistroAnterior|null $registroAnterior)
    {
        if ($registroAnterior==null) {
            $this->PrimerRegistro = true;
            $this->RegistroAnterior = null;
        } else {
            $this->PrimerRegistro = false;
            $this->RegistroAnterior = $registroAnterior;
        }
    }

    public function toArray() {
        if ($this->PrimerRegistro) {
            return ['PrimerRegistro'=>'S'];
        }
        return ['RegistroAnterior'=>$this->RegistroAnterior->toArray()];
    }
}