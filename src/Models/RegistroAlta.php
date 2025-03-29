<?php
namespace jdg\Verifactu\Models;
use jdg\Verifactu\Listas;

class RegistroAlta
{
    /**
     * Identificación de la versión actual del esquema o estructura de información utilizada para la generación y conservación / remisión de los registros de facturación. Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación.
     */
    public Listas\L15 $IDVersion;
    public IDFactura $IDFactura;
    /**
     * Dato adicional de contenido libre con el objetivo de que se pueda asociar opcionalmente información interna del sistema informático de facturación al registro de facturación. Este dato puede ayudar a completar la identificación o calificación de la factura y/o su registro de facturación.
     */
    public ?string $RefExterna = null;
    /**
     * Nombre-razón social del obligado a expedir la factura.
     */
    public string $NombreRazonEmisor;
    /**
     * Indicador que especifica que se trata de una subsanación de un registro de facturación de alta previamente generado, por lo que el contenido de este nuevo registro de facturación es el correcto y el que deberá tenerse en cuenta. Si no se informa este campo se entenderá que tiene valor "N" (Alta Normal-Inicial). Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación.
     */
    public ?Listas\L4 $Subsanacion = null;
    /**
     * Indicador que especifica que se está generando -para volverlo a remitir- un nuevo registro de facturación de alta subsanado tras haber sido rechazado en su remisión inmediatamente anterior, es decir, en el último envío que contenía ese registro de facturación de alta rechazado. Si no se informa este campo se entenderá que tiene valor "N". Solo es necesario informarlo en caso de remisión voluntaria «VERI*FACTU». Este campo forma parte del detalle de las circunstancias de generación de los registros de facturación.
     */
    public ?Listas\L17 $RechazoPrevio = null;
    /**
     * Especificación del tipo de factura: factura completa, factura simplificada, factura emitida en sustitución de facturas simplificadas o factura rectificativa.
     */
    public Listas\L2 $TipoFactura;
    /**
     * Campo que identifica si el tipo de factura rectificativa es por sustitución o por diferencia.
     */
    public ?Listas\L3 $TipoRectificativa = null;
    /**
     * @var IDFacturaRectificada[]
     */
    public array $FacturasRectificadas = [];
    /**
     * @var IDFacturaSustituida[]
     */
    public array $FacturasSustituidas = [];
    public ?ImporteRectificacion $ImporteRectificacion;
    /**
     * Fecha en la que se ha realizado la operación siempre que sea diferente a la fecha de expedición.
     */
    public ?string $FechaOperacion;
    /**
     * Descripción del objeto de la factura.
     */
    public string $DescripcionOperacion;
    /**
     * Factura simplificada Articulo 7.2 Y 7.3 RD 1619/2012. Si no se informa este campo se entenderá que tiene valor “N".
     */
    public ?Listas\L4 $FacturaSimplificadaArt7273;
    /**
     * Factura sin identificación destinatario artículo 6.1.d) RD 1619/2012. Si no se informa este campo se entenderá que tiene valor “N".
     */
    public ?Listas\L5 $FacturaSinIdentifDestinatarioArt61d = null;
    public ?Listas\L14 $Macrodato = null;







    
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