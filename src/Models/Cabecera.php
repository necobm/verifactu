<?php
namespace jdg\Verifactu\Models;

class Cabecera
{
    public ObligadoEmision $ObligadoEmision;
    public ?Representante $Representante = null;
    public ?RemisionVoluntaria $RemisionVoluntaria = null;
    public ?RemisionRequerimiento $RemisionRequerimiento = null;

    public function toArray() {
        $data = ['ObligadoEmision'=>$this->ObligadoEmision->toArray()];
        if ($this->Representante!=null) {
            $data['Representante'] = $this->Representante->toArray();
        }
        if ($this->RemisionVoluntaria!=null) {
            $data['RemisionVoluntaria'] = $this->RemisionVoluntaria->toArray();
        }
        if ($this->RemisionRequerimiento!=null) {
            $data['RemisionRequerimiento'] = $this->RemisionRequerimiento->toArray();
        }
        return $data;
    }
}