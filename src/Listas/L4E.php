<?php 
namespace jdg\Verifactu\Listas;

// L4E: Destinatario/Tercero
enum L4E: string {
    case DESTINATARIO = 'D';
    case TERCERO = 'T';

    public function description(): string {
        return match($this) {
            self::DESTINATARIO => 'Destinatario',
            self::TERCERO => 'Tercero',
        };
    }
}