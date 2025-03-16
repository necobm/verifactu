<?php 
namespace jdg\Verifactu;

// L10: Exenciones
enum L10: string {
    case E1 = 'E1';
    case E2 = 'E2';
    case E3 = 'E3';
    case E4 = 'E4';
    case E5 = 'E5';
    case E6 = 'E6';

    public function description(): string {
        return match($this) {
            self::E1 => 'Exenta por el artículo 20',
            self::E2 => 'Exenta por el artículo 21',
            self::E3 => 'Exenta por el artículo 22',
            self::E4 => 'Exenta por los artículos 23 y 24',
            self::E5 => 'Exenta por el artículo 25',
            self::E6 => 'Exenta por otros',
        };
    }
}