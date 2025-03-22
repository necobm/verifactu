<?php 
namespace jdg\Verifactu\Listas;

// L15 (Version)
enum L15: string {
    case V1 = '1.0';

    public function description(): string {
        return 'Versión actual (1.0) del esquema utilizado';
    }
}