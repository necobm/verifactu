<?php 
namespace jdg\Verifactu\Listas;

// L6
enum L17: string {
    case NO_RECHAZO = 'N';
    case RECHAZO_PREVIO = 'S';
    case REGISTRO_NO_EXISTENTE = 'X';

    public function description(): string {
        return match($this) {
            self::NO_RECHAZO => 'No ha habido rechazo previo por la AEAT.',
            self::RECHAZO_PREVIO => 'Ha habido rechazo previo por la AEAT. No deberían existir operaciones de alta con valores ("Subsanacion"="N" y "RechazoPrevio"="S"), por lo que no se admiten.',
            self::REGISTRO_NO_EXISTENTE => 'Independientemente de si ha habido o no algún rechazo previo por la AEAT, el registro de facturación no existe en la AEAT.',
        };
    }
}