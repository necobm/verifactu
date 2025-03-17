<?php 
namespace jdg\Verifactu\Listas;

// L5: Sí/No
enum L5: string {
    case SI = 'S';
    case NO = 'N';

    public function description(): string {
        return match($this) {
            self::SI => 'Sí',
            self::NO => 'No',
        };
    }
}