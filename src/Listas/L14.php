<?php 
namespace jdgOpenCode\verifactu\Listas;

// L14: Sí/No
enum L14: string {
    case SI = 'S';
    case NO = 'N';

    public function description(): string {
        return match($this) {
            self::SI => 'Sí',
            self::NO => 'No',
        };
    }
}