<?php 
namespace jdg\Verifactu\Listas;

// L4: Sí/No
enum L4: string {
    case SI = 'S';
    case NO = 'N';

    public function description(): string {
        return match($this) {
            self::SI => 'Sí',
            self::NO => 'No',
        };
    }
}