<?php 
namespace jdg\Verifactu\Listas;

// L1: Tipos de Impuestos
enum L1: string {
    case IVA = '01';
    case IPSI_CEUTA_MELILLA = '02';
    case IGIC = '03';
    case OTROS = '05';

    public function description(): string {
        return match($this) {
            self::IVA => 'Impuesto sobre el Valor Añadido (IVA)',
            self::IPSI_CEUTA_MELILLA => 'Impuesto sobre la Producción, los Servicios y la Importación (IPSI) de Ceuta y Melilla',
            self::IGIC => 'Impuesto General Indirecto Canario (IGIC)',
            self::OTROS => 'Otros',
        };
    }
}