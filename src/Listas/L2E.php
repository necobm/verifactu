<?php 
namespace jdg\Verifactu\Listas;

// L2E
enum L2E: string {
    case INICIO_NO_VERIFACTU = '01';
    case FIN_NO_VERIFACTU = '02';
    case DETECCION_ANOMALIAS_FACTURACION = '03';
    case ANOMALIAS_FACTURACION = '04';
    case DETECCION_ANOMALIAS_EVENTOS = '05';
    case ANOMALIAS_EVENTOS = '06';
    case RESTAURACION_COPIA = '07';
    case EXPORTACION_FACTURACION = '08';
    case EXPORTACION_EVENTOS = '09';
    case RESUMEN_EVENTOS = '10';
    case OTROS_EVENTOS = '90';

    public function description(): string {
        return match($this) {
            self::INICIO_NO_VERIFACTU => 'Inicio del funcionamiento del sistema informático como «NO VERI*FACTU».',
            self::FIN_NO_VERIFACTU => 'Fin del funcionamiento del sistema informático como «NO VERI*FACTU».',
            self::DETECCION_ANOMALIAS_FACTURACION => 'Lanzamiento del proceso de detección de anomalías en los registros de facturación.',
            self::ANOMALIAS_FACTURACION => 'Detección de anomalías en la integridad, inalterabilidad y trazabilidad de registros de facturación.',
            self::DETECCION_ANOMALIAS_EVENTOS => 'Lanzamiento del proceso de detección de anomalías en los registros de evento.',
            self::ANOMALIAS_EVENTOS => 'Detección de anomalías en la integridad, inalterabilidad y trazabilidad de registros de evento.',
            self::RESTAURACION_COPIA => 'Restauración de copia de seguridad, cuando ésta se gestione desde el propio sistema informático de facturación.',
            self::EXPORTACION_FACTURACION => 'Exportación de registros de facturación generados en un periodo.',
            self::EXPORTACION_EVENTOS => 'Exportación de registros de evento generados en un periodo.',
            self::RESUMEN_EVENTOS => 'Registro resumen de eventos.',
            self::OTROS_EVENTOS => 'Otros tipos de eventos a registrar voluntariamente por la persona o entidad productora del sistema informático.',
        };
    }
}