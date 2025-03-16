<?php 
namespace jdg\Verifactu;

// L6
enum L16: string {
    case E = 'E';
    case D = 'D';
    case T = 'T';

    public function description(): string {
        return match($this) {
            self::E => 'Expedidor (obligado a Expedir la factura anulada).',
            self::D => 'Destinatario',
            self::T => 'Tercero',
        };
    }
}