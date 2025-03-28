<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\Listas;

class SistemaInformatico
{
    public string $NombreRazon;
    public string $NIF;
    public string $NombreSistemaInformatico;
    public string $IdSistemaInformatico;
    public string $Version;
    public string $NumeroInstalacion;
    public Listas\L4 $TipoUsoPosibleSoloVerifactu;
    public Listas\L4 $TipoUsoPosibleMultiOT;
    public Listas\L4 $IndicadorMultiplesOT;
}