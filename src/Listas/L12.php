<?php 
namespace jdgOpenCode\verifactu\Listas;

// L12 (SHA-256)
enum L12: string {
    case SHA256 = '01';

    public function description(): string {
        return 'SHA-256';
    }
}