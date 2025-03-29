<?php
namespace jdg\Verifactu\Models;

class RegistroFactura
{
    /**
     * Datos del registro de facturación de alta. Ver su diseño de bloque: «RegistroAlta».
     */
    public RegistroAlta $RegistroAlta;
    /**
     * Datos del registro de facturación de anulación. Ver su diseño de bloque: «RegistroAnulacion».
     */
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