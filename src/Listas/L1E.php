<?php 
namespace jdgOpenCode\verifactu\Listas;

// L1E
enum L1E: string {
    case INTEGRIDAD_HUELLA = '01';
    case INTEGRIDAD_FIRMA = '02';
    case INTEGRIDAD_OTROS = '03';
    case TRAZA_REGISTRO_ANTERIOR = '04';
    case TRAZA_REGISTRO_POSTERIOR = '05';
    case TRAZA_REGISTRO_OTROS = '06';
    case TRAZA_HUELLA_CORRESPONDENCIA = '07';
    case TRAZA_HUELLA_ANTERIOR = '08';
    case TRAZA_HUELLA_OTROS = '09';
    case TRAZA_CADENA_OTROS = '10';
    case TRAZA_FECHAS_ANTERIOR = '11';
    case TRAZA_FECHAS_POSTERIOR = '12';
    case TRAZA_FECHAS_FUTURO = '13';
    case TRAZA_FECHAS_OTROS = '14';
    case TRAZA_OTROS = '15';
    case OTROS = '90';

    public function description(): string {
        return match($this) {
            self::INTEGRIDAD_HUELLA => 'Integridad-huella',
            self::INTEGRIDAD_FIRMA => 'Integridad-firma',
            self::INTEGRIDAD_OTROS => 'Integridad - Otros',
            self::TRAZA_REGISTRO_ANTERIOR => 'Trazabilidad-cadena-registro - Reg. no primero pero con reg. anterior no anotado o inexistente',
            self::TRAZA_REGISTRO_POSTERIOR => 'Trazabilidad-cadena-registro - Reg. no último pero con reg. posterior no anotado o inexistente',
            self::TRAZA_REGISTRO_OTROS => 'Trazabilidad-cadena-registro - Otros',
            self::TRAZA_HUELLA_CORRESPONDENCIA => 'Trazabilidad-cadena-huella - Huella del reg. no se corresponde con la "huella del reg. anterior" almacenada en el registro posterior',
            self::TRAZA_HUELLA_ANTERIOR => 'Trazabilidad-cadena-huella - Campo "huella del reg. anterior" no se corresponde con la huella del reg. anterior',
            self::TRAZA_HUELLA_OTROS => 'Trazabilidad-cadena-huella - Otros',
            self::TRAZA_CADENA_OTROS => 'Trazabilidad-cadena - Otros',
            self::TRAZA_FECHAS_ANTERIOR => 'Trazabilidad-fechas - Fecha-hora anterior a la fecha del reg. anterior',
            self::TRAZA_FECHAS_POSTERIOR => 'Trazabilidad-fechas - Fecha-hora posterior a la fecha del reg. posterior',
            self::TRAZA_FECHAS_FUTURO => 'Trazabilidad-fechas - Reg. con fecha-hora de generación posterior a la fecha-hora actual del sistema',
            self::TRAZA_FECHAS_OTROS => 'Trazabilidad-fechas - Otros',
            self::TRAZA_OTROS => 'Trazabilidad - Otros',
            self::OTROS => 'Otros',
        };
    }
}