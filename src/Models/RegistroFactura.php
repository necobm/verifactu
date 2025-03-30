<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\VeriFactuDateTimeHelper;
use jdg\Verifactu\VeriFactuStringHelper;
use jdg\Verifactu\Listas;

class RegistroFactura
{
    /**
     * Datos del registro de facturación de alta. Ver su diseño de bloque: «RegistroAlta».
     */
    public ?RegistroAlta $RegistroAlta = null;
    /**
     * Datos del registro de facturación de anulación. Ver su diseño de bloque: «RegistroAnulacion».
     */
    public ?RegistroAnulacion $RegistroAnulacion = null;

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

    public function toArray() {
        if ($this->RegistroAlta != null) {
            return ['RegistroAlta'=>$this->RegistroAlta->toArray()];
        }
        return ['RegistroAnulacion'=>$this->RegistroAnulacion->toArray()];
    }
}