<?php 
namespace jdg\Verifactu;

// L2: Tipos de Factura
enum L2: string {
    case F1 = 'F1';
    case F2 = 'F2';
    case F3 = 'F3';
    case R1 = 'R1';
    case R2 = 'R2';
    case R3 = 'R3';
    case R4 = 'R4';
    case R5 = 'R5';

    public function description(): string {
        return match($this) {
            self::F1 => 'Factura (art. 6, 7.2 y 7.3 del RD 1619/2012)',
            self::F2 => 'Factura Simplificada y Facturas sin identificación del destinatario art. 6.1.d) RD 1619/2012',
            self::F3 => 'Factura emitida en sustitución de facturas simplificadas facturadas y declaradas',
            self::R1 => 'Factura Rectificativa (Error fundado en derecho y Art. 80 Uno Dos y Seis LIVA)',
            self::R2 => 'Factura Rectificativa (Art. 80.3)',
            self::R3 => 'Factura Rectificativa (Art. 80.4)',
            self::R4 => 'Factura Rectificativa (Resto)',
            self::R5 => 'Factura Rectificativa en facturas simplificadas',
        };
    }
}