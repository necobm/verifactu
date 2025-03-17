<?php 
namespace jdg\Verifactu\Listas;

// L3E: Sí/No
enum L3E: string {
    case SI = 'S';
    case NO = 'N';

    public function description(): string {
        return match($this) {
            self::SI => 'Sí',
            self::NO => 'No',
        };
    }
}