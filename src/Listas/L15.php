<?php 
namespace jdg\Verifactu\Listas;

// L12 (SHA-256)
enum L12: string {
    case V1 = '1.0';

    public function description(): string {
        return 'Versión actual (1.0) del esquema utilizado';
    }
}