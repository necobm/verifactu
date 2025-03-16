<?php 
namespace jdg\Verifactu;

// L8A y L8B: Régimenes Fiscales
enum L8A: string {
    case REGIMEN_GENERAL = '01';
    case EXPORTACION = '02';
    // ... Resto de casos

    public function description(): string {
        return match($this) {
            self::REGIMEN_GENERAL => 'Operación de régimen general.',
            self::EXPORTACION => 'Exportación.',
            // ... Resto de descripciones
        };
    }
}

enum L8B: string {
    case REGIMEN_GENERAL = '01';
    case EXPORTACION = '02';
    // ... Casos específicos de IGIC

    public function description(): string {
        return match($this) {
            self::REGIMEN_GENERAL => 'Operación de régimen general.',
            self::EXPORTACION => 'Exportación.',
            // ... Descripciones ajustadas a IGIC
        };
    }
}