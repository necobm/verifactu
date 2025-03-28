<?php
namespace jdg\Verifactu\Models;

class RegistroFactura
{
    public RegistroAlta $RegistroAlta;
    public RegistroAnulacion $RegistroAnulacion;

    public function __construct(RegistroAlta|RegistroAnulacion|null $registro = null)
    {
        if ($registro instanceof RegistroAlta) {
            $this->setRegistroAlta($registro);
        } elseif ($registro instanceof RegistroAnulacion) {
            $this->setRegistroAnulacion($registro);
        } elseif ($registro !== null) {
            throw new \InvalidArgumentException(
                'Constructor argument must be an instance of RegistroAlta or RegistroAnulacion.'
            );
        }
    }    

    public function setRegistroAlta(RegistroAlta $registroAlta) {
        $this->RegistroAlta = $registroAlta;
    }

    public function setRegistroAnulacion(RegistroAnulacion $registroAnulacion) {
        $this->RegistroAnulacion = $registroAnulacion;
    }
}