<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\VeriFactuHashGenerator;
use jdgOpenCode\verifactu\VeriFactuDateTimeHelper;
use jdgOpenCode\verifactu\VeriFactuStringHelper;
use jdgOpenCode\verifactu\Listas;

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
    public ?ImporteRectificacion $ImporteRectificacion = null;
    /**
     * Fecha en la que se ha realizado la operación siempre que sea diferente a la fecha de expedición.
     */
    public ?\DateTime $FechaOperacion = null;
    /**
     * Descripción del objeto de la factura.
     */
    public string $DescripcionOperacion;
    /**
     * Factura simplificada Articulo 7.2 Y 7.3 RD 1619/2012. Si no se informa este campo se entenderá que tiene valor “N".
     */
    public ?Listas\L4 $FacturaSimplificadaArt7273 = null;
    /**
     * Factura sin identificación destinatario artículo 6.1.d) RD 1619/2012. Si no se informa este campo se entenderá que tiene valor “N".
     */
    public ?Listas\L5 $FacturaSinIdentifDestinatarioArt61d = null;
    /**
     * Identificador que especifica aquellas facturas con base o importe de la factura superior al umbral especificado. Este campo es necesario porque contribuye a completar el detalle de la tipología de la factura. Si no se informa este campo se entenderá que tiene valor “N”.
     */
    public ?Listas\L14 $Macrodato = null;
    /**
     * Identificador que especifica si la factura ha sido expedida materialmente por un tercero o por el destinatario (contraparte). 
     */
    public ?Listas\L6 $EmitidaPorTerceroODestinatario = null;
    public ?Tercero $Tercero = null;
    /**
     * @var Destinatario[]
     */
    public array $Destinatarios = [];    
    /**
     * Identificador que especifica si tiene minoración de la base imponible por la concesión de cupones, bonificaciones o descuentos cuando solo se expide el original de la factura. Este campo es necesario porque contribuye a completar el detalle de la tipología de la factura. Si no se informa este campo se entenderá que tiene valor “N”.
     */
    public ?Listas\L4 $Cupon = null;
    /**
    * @var Desglose[]
    */
    public array $Desgloses = [];
    /**
     * Importe total de la cuota (sumatorio de la Cuota Repercutida y Cuota de Recargo de Equivalencia). 
     */
    public string $CuotaTotal = '0.00';
    /**
     * Importe total de la factura. Se detallará la forma de calcularlo en la documentación correspondiente en la sede electrónica de la AEAT (documento de validaciones...).
     */
    public string $ImporteTotal = '0.00';
    public Encadenamiento $Encadenamiento;
    public SistemaInformatico $SistemaInformatico;
    /**
     * Fecha, hora y huso horario de generación del registro de facturación. El huso horario es el que está usando el sistema informático de facturación en el momento de generación del registro de facturación.
     */
    public ?string $FechaHoraHusoGenRegistro = null;
    /**
     * Número de registro obtenido al enviar la autorización en materia de facturación o de libros registro a que se refiere la disposición adicional primera del Real Decreto que aprueba el Reglamento. Este campo forma parte del detalle de las circunstancias de generación del registro de facturación.
     */
    public ?string $NumRegistroAcuerdoFacturacion = null;
    /**
     * Identificación del acuerdo (resolución) a que se refiere el artículo 5 del Reglamento. Este campo forma parte del detalle de las circunstancias de generación del registro de facturación.
     */
    public ?string $IdAcuerdoSistemaInformatico = null;
    /**
     * Tipo de algoritmo aplicado a cierto contenido del registro de facturación para obtener la huella o «hash».
     */
    public Listas\L12 $TipoHuella = Listas\L12::SHA256;
    /**
     * Huella o «hash» de cierto contenido de este registro de facturación. Dicho contenido se detallará en la documentación correspondiente en la sede electrónica de la AEAT (documento de huella...).
     */
    public ?string $Huella = null;

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

    public function toArray() {
        $data = [
            'IDVersion'=>$this->IDVersion->value,
            'IDFactura'=>$this->IDFactura->toArray(),
        ];
        if ($this->RefExterna!=null) {
            $data['RefExterna'] = VeriFactuStringHelper::sanitizeString($this->RefExterna);
        }
        $data['NombreRazonEmisor'] = VeriFactuStringHelper::sanitizeString($this->NombreRazonEmisor);
        if ($this->Subsanacion!=null) {
            $data['Subsanacion'] = $this->Subsanacion->value;
        }
        if ($this->RechazoPrevio!=null) {
            $data['RechazoPrevio'] = $this->RechazoPrevio->value;
        }
        $data['TipoFactura'] = $this->TipoFactura->value;
        if ($this->TipoRectificativa!=null) {
            $data['TipoRectificativa'] = $this->TipoRectificativa->value;
        }
        if (count($this->FacturasRectificadas)>0) {
            $data['FacturasRectificadas'] = [];
            foreach($this->FacturasRectificadas as $facturaRectificada) {
                $data['FacturasRectificadas'][] = $facturaRectificada->toArray();
            }
        }
        if (count($this->FacturasSustituidas)>0) {
            $data['FacturasSustituidas'] = [];
            foreach($this->FacturasSustituidas as $facturaSustituida) {
                $data['FacturasSustituidas'][] = $facturaSustituida->toArray();
            }
        }
        if ($this->ImporteRectificacion!=null) {
            $data['ImporteRectificacion'] =  $this->ImporteRectificacion->toArray();
        }
        if ($this->FechaOperacion!=null) {
            $data['FechaOperacion'] =  VeriFactuDateTimeHelper::formatDate($this->FechaOperacion);
        }
        $data['DescripcionOperacion'] =  VeriFactuStringHelper::sanitizeString($this->DescripcionOperacion);
        if ($this->FacturaSimplificadaArt7273!=null) {
            $data['FacturaSimplificadaArt7273'] =  $this->FacturaSimplificadaArt7273->value;
        }
        if ($this->FacturaSinIdentifDestinatarioArt61d!=null) {
            $data['FacturaSinIdentifDestinatarioArt61d'] =  $this->FacturaSinIdentifDestinatarioArt61d->value;
        }
        if ($this->Macrodato!=null) {
            $data['Macrodato'] =  $this->Macrodato->value;
        }
        if ($this->EmitidaPorTerceroODestinatario!=null) {
            $data['EmitidaPorTerceroODestinatario'] =  $this->EmitidaPorTerceroODestinatario->value;
        }
        if ($this->Tercero!=null) {
            $data['Tercero'] =  $this->Tercero->toArray();
        }
        if (count($this->Destinatarios)>0) {
            $data['Destinatarios'] = [];
            foreach($this->Destinatarios as $destinatario) {
                $data['Destinatarios'][] = $destinatario->toArray();
            }
        }
        if ($this->Cupon!=null) {
            $data['Cupon'] =  $this->Cupon->value;
        }

        $this->CuotaTotal = bcadd($this->CuotaTotal, 0, 2);
        $this->ImporteTotal = bcadd($this->ImporteTotal, 0, 2);
        if (count($this->Desgloses)>0) {
            $cuotaTotal = 0.0;
            $baseImponibleTotal = 0.0;
            $data['Desglose'] = [];
            foreach($this->Desgloses as $desglose) {
                $data['Desglose'][] = $desglose->toArray();

                $cuotaTotal = bcadd($cuotaTotal, $desglose->CuotaRepercutida, 2);
                if ($desglose->ClaveRegimen != Listas\L8A::RECARGO_EQUIVALENCIA) {
                    $baseImponibleTotal = bcadd($baseImponibleTotal, $desglose->BaseImponibleOimporteNoSujeto, 2);
                }
            }
            if ($this->CuotaTotal != $cuotaTotal) {
                throw new \Exception('La suma de cuotas ('.$this->CuotaTotal.') en los desgloses no coincide con la cuota total ('.$cuotaTotal.').');
            }
            $importeTotal = bcadd($cuotaTotal, $baseImponibleTotal, 2);
            if ($this->ImporteTotal != $importeTotal) {
                throw new \Exception('La suma de importes en los desgloses no coincide con el importe total.');
            }
        }

        $data['CuotaTotal'] =  $this->CuotaTotal;
        $data['ImporteTotal'] =  $this->ImporteTotal;
        $data['Encadenamiento'] =  $this->Encadenamiento->toArray();
        $data['SistemaInformatico'] =  $this->SistemaInformatico->toArray();
        if ($this->NumRegistroAcuerdoFacturacion!=null) {
            $data['NumRegistroAcuerdoFacturacion'] =  $this->NumRegistroAcuerdoFacturacion;
        }
        if ($this->IdAcuerdoSistemaInformatico!=null) {
            $data['IdAcuerdoSistemaInformatico'] =  $this->IdAcuerdoSistemaInformatico;
        }
        $data['TipoHuella'] =  $this->TipoHuella->value;
        if ($this->Huella!=null && $this->FechaHoraHusoGenRegistro!=null) {
            // Use the given hash/timestamp (for testing)
        } else {
            $this->FechaHoraHusoGenRegistro = VeriFactuDateTimeHelper::nowIso8601();
            $invoiceData = [
                'IDEmisorFactura' => $this->IDFactura->IDEmisorFactura,
                'NumSerieFactura' => $this->IDFactura->NumSerieFactura,
                'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($this->IDFactura->FechaExpedicionFactura),
                'TipoFactura' => $this->TipoFactura->value,
                'CuotaTotal' => $cuotaTotal,
                'ImporteTotal' => $importeTotal,
                'Huella' => $this->Encadenamiento->PrimerRegistro?'':$this->Encadenamiento->RegistroAnterior->Huella,
                'FechaHoraHusoGenRegistro' => $this->FechaHoraHusoGenRegistro,
            ];
            $hashInvoice = VeriFactuHashGenerator::generateHashInvoice($invoiceData);
            $this->Huella = $hashInvoice['hash'];
        }
        $data['Huella'] =  $this->Huella;
        $data['FechaHoraHusoGenRegistro'] =  $this->FechaHoraHusoGenRegistro;
        return $data;
    }
}