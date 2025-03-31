<?php
namespace jdgOpenCode\verifactu\Models;
use jdgOpenCode\verifactu\Listas;

class SistemaInformatico
{
    /**
     * Nombre-razón social de la persona o entidad productora (ver * NOTA aclaratoria al final del bloque «SistemaInformatico»).
     */
    public string $NombreRazon;
    /**
     * NIF de la persona o entidad productora (ver * NOTA aclaratoria al final del bloque «SistemaInformatico»).
     */
    public ?string $NIF = null;
    public ?IDOtro $IDOtro = null;
    /**
     * Nombre dado por la persona o entidad productora a su sistema informático de facturación (SIF) que, una vez instalado, se constituye en el SIF utilizado. Obligatorio en registros de facturación de alta y de anulación, y opcional en registros de evento.
     */
    public string $NombreSistemaInformatico;
    /**
     * Código identificativo dado por la persona o entidad productora a su sistema informático de facturación (SIF) que, una vez instalado, se constituye en el SIF utilizado. Deberá distinguirlo de otros posibles SIF distintos que produzca esta misma persona o entidad productora. Se detallarán las posibles restricciones a sus valores en la documentación correspondiente en la sede electrónica de la AEAT (documento de validaciones...).
     */
    public string $IdSistemaInformatico;
    /**
     * Identificación de la versión del sistema informático de facturación (SIF) que se ejecuta en el sistema informático de facturación utilizado.
     */
    public string $Version;
    /**
     * Número de instalación del sistema informático de facturación (SIF) utilizado. Deberá distinguirlo de otros posibles SIF utilizados para realizar la facturación del obligado a expedir facturas, es decir, de otras posibles instalaciones de SIF pasadas, presentes o futuras utilizadas para realizar la facturación del obligado a expedir facturas, incluso aunque en dichas instalaciones se emplee el mismo SIF de un productor.
     */
    public string $NumeroInstalacion;
    /**
     * Especifica si para cumplir el Reglamento el sistema informático de facturación solo puede funcionar exclusivamente como «VERI*FACTU» (valor "S") o puede funcionar también como «NO VERI*FACTU» (valor "N"). Obligatorio en registros de facturación de alta y de anulación. No aplica en registros de evento.
     */
    public ?Listas\L4 $TipoUsoPosibleSoloVerifactu = null;
    /**
     * Especifica si el sistema informático de facturación permite llevar independientemente la facturación de varios obligados tributarios (valor "S") o solo de uno (valor "N"). Obligatorio en registros de facturación de alta y de anulación, y opcional en registros de evento.
     */
    public ?Listas\L4 $TipoUsoPosibleMultiOT = null;
    /**
     * Indicador de que el sistema informático, en el momento de la generación de este registro, está soportando la facturación de más de un obligado tributario. Este valor deberá obtenerlo automáticamente el sistema informático a partir del número de obligados tributarios contenidos y/o gestionados en él en ese momento, independientemente de su estado operativo (alta, baja...), no pudiendo obtenerse a partir de otra información ni ser introducido directamente por el usuario del sistema informático ni cambiado por él. El valor "N" significará que el sistema informático solo contiene y/o gestiona un único obligado tributario (de alta o de baja o en cualquier otro estado), que se corresponderá con el obligado a expedir factura de este registro de facturación. En cualquier otro caso, se deberá informar este campo con el valor "S". Obligatorio en registros de facturación de alta y de anulación, y opcional en registros de evento.
     */
    public ?Listas\L4 $IndicadorMultiplesOT = null;

    public function toArray() {
        $data = [
            'NombreRazon' => $this->NombreRazon
        ];
        if (($this->NIF==null && $this->IDOtro==null) || ($this->NIF!=null && $this->IDOtro!=null)) {
            throw new \Exception('Desinatario requiere NIF ó IDOtro.');
        }
        if ($this->NIF!=null) {
            $data['NIF'] = $this->NIF;
        } else
        if ($this->IDOtro!=null) {
            $data['IDOtro'] = $this->IDOtro->toArray();
        }
        if ($this->NombreSistemaInformatico!=null) {
            $data['NombreSistemaInformatico'] = $this->NombreSistemaInformatico;
        }
        $data['IdSistemaInformatico'] = $this->IdSistemaInformatico;
        $data['Version'] = $this->Version;
        $data['NumeroInstalacion'] = $this->NumeroInstalacion;
        $data['TipoUsoPosibleSoloVerifactu'] = $this->TipoUsoPosibleSoloVerifactu->value;
        $data['TipoUsoPosibleMultiOT'] = $this->TipoUsoPosibleMultiOT->value;
        $data['IndicadorMultiplesOT'] = $this->IndicadorMultiplesOT->value;
        return $data;
    }
}