<?php 
namespace jdg\Verifactu\Listas;

// L3: Motivo de Sustitución/Diferencias
enum L3: string {
    case SUSTITUCION = 'S';
    case DIFERENCIAS = 'I';

    public function description(): string {
        return match($this) {
            self::SUSTITUCION => 'Por sustitución',
            self::DIFERENCIAS => 'Por diferencias',
        };
    }
}