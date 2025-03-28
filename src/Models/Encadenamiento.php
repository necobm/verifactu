<?php
namespace jdg\Verifactu\Models;

class Encadenamiento
{
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
}