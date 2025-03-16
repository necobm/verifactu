<?php 
namespace jdg\Verifactu;

// L7: Documentos de Identificación
enum L7: string {
    case NIF_IVA = '02';
    case PASAPORTE = '03';
    case DOC_IDENTIFICACION = '04';
    case CERT_RESIDENCIA = '05';
    case OTRO_DOC = '06';
    case NO_CENSADO = '07';

    public function description(): string {
        return match($this) {
            self::NIF_IVA => 'NIF-IVA',
            self::PASAPORTE => 'Pasaporte',
            self::DOC_IDENTIFICACION => 'Documento oficial de identificación expedido por el país o territorio de residencia',
            self::CERT_RESIDENCIA => 'Certificado de residencia',
            self::OTRO_DOC => 'Otro documento probatorio',
            self::NO_CENSADO => 'No censado',
        };
    }
}