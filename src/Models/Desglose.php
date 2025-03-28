<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\Listas;

class Desglose
{
    public Listas\L8A $ClaveRegimen;
    public Listas\L9 $CalificacionOperacion;
    public string $TipoImpositivo;
    public string $BaseImponibleOimporteNoSujeto;
    public string $CuotaRepercutida;
}