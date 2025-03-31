<?php
namespace jdgOpenCode\verifactu\Models;

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

    public function add(RegistroFactura $registroFactura) {
        $this->RegistroFactura[] = $registroFactura;
    }

    public function __construct(Cabecera $cabecera, RegistroFactura $registroFactura)
    {
        $this->setCabecera($cabecera);
        $this->add($registroFactura);
    }

    public function toArray() {
        $data = ['Cabecera'=>$this->Cabecera->toArray()];
        $registroAnterior = null;
        foreach($this->RegistroFactura as $registroFactura) {
            if ($registroAnterior!=null) {
                if ($registroFactura->RegistroAlta!=null) {
                    $registroFactura->RegistroAlta->Encadenamiento = new Encadenamiento($registroAnterior);
                } else {
                    $registroFactura->RegistroAnulacion->Encadenamiento = new Encadenamiento($registroAnterior);
                }
            }
            $data['RegistroFactura'][] = $registroFactura->toArray();
            if ($registroFactura->RegistroAlta!=null) {
                $registroAnterior = new RegistroAnterior(
                    $registroFactura->RegistroAlta->IDFactura->IDEmisorFactura,
                    $registroFactura->RegistroAlta->IDFactura->NumSerieFactura,
                    $registroFactura->RegistroAlta->IDFactura->FechaExpedicionFactura,
                    $registroFactura->RegistroAlta->Huella
                );
            } else {
                $registroAnterior = new RegistroAnterior(
                    $registroFactura->RegistroAnulacion->IDFactura->IDEmisorFactura,
                    $registroFactura->RegistroAnulacion->IDFactura->NumSerieFactura,
                    $registroFactura->RegistroAnulacion->IDFactura->FechaExpedicionFactura,
                    $registroFactura->RegistroAnulacion->Huella
                );
            }
        }
        return $data;
    }
}