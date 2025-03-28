<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\Listas;

class RegistroAlta
{
    public Listas\L15 $IDVersion;
    public IDFactura $IDFactura;
    public string $NombreRazonEmisor;
    public Listas\L2 $TipoFactura;
    public string $DescripcionOperacion;
    /**
     * @var Destinatario[]
     */
    public array $Destinatarios = [];    
    /**
    * @var Desglose[]
    */
    public array $Desgloses = [];
    public string $CuotaTotal = '0.00';
    public string $ImporteTotal = '0.00';
    public Encadenamiento $Encadenamiento;
    public SistemaInformatico $SistemaInformatico;
    public ?string $Huella;
    public ?string $FechaHoraHusoGenRegistro;

    public function __construct()
    {
        $this->FechaHoraHusoGenRegistro = null;
        $this->Huella = null;
    }

    public function addDestinatario(Destinatario $destinatario) {
        $this->Destinatarios[] = $destinatario;
    }

    public function addDesglose(Desglose $desglose) {
        $this->Desgloses[] = $desglose;
        bcadd($this->CuotaTotal, $desglose->CuotaRepercutida, 2);

        bcadd($this->ImporteTotal, $desglose->CuotaRepercutida, 2);
        if ($desglose->ClaveRegimen != Listas\L8A::RECARGO_EQUIVALENCIA) {
            bcadd($this->ImporteTotal, $desglose->BaseImponibleOimporteNoSujeto, 2);
        }        
    }
}