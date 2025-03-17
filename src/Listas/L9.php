<?php 
namespace jdg\Verifactu\Listas;

// L9: Situación de la Operación
enum L9: string {
    case S1 = 'S1';
    case S2 = 'S2';
    case N1 = 'N1';
    case N2 = 'N2';

    public function description(): string {
        return match($this) {
            self::S1 => 'Operación Sujeta y No exenta - Sin inversión del sujeto pasivo.',
            self::S2 => 'Operación Sujeta y No exenta - Con Inversión del sujeto pasivo',
            self::N1 => 'Operación No Sujeta artículo 7, 14, otros.',
            self::N2 => 'Operación No Sujeta por Reglas de localización.',
        };
    }
}