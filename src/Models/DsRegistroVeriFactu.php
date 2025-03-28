<?php
namespace jdg\Verifactu\Models;

class DsRegistroVeriFactu
{
    public Cabecera $Cabecera;
    
    /**
     * @var RegistroFactura[]
     */
    public array $RegistroFactura;

    public function setCabecera(Cabecera $cabecera) {
        $this->Cabecera = $cabecera;
    }

    public function AddRegistroAlta(RegistroFactura $registroFactura) {
        $this->RegistroFactura[] = $registroFactura;
    }

    public function __construct(Cabecera $cabecera, RegistroFactura $registroFactura)
    {
        $this->setCabecera($cabecera);
        $this->AddRegistroAlta($registroFactura);
    }
}