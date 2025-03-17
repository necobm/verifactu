<?php 
namespace jdg\Verifactu;

// L8A y L8B: Régimenes Fiscales
enum L8A: string {
    case REGIMEN_GENERAL = '01';
    case EXPORTACION = '02';
    case BIENES_USADOS = '03';
    case ORO_INVERSION = '04';
    case AGENCIAS_VIAJES = '05';
    case GRUPO_ENTIDADES = '06';
    case CRITERIO_CAJA = '07';
    case IPSI_IGIC = '08';
    case AGENCIAS_VIAJE_MEDIADORAS = '09';
    case COBROS_TERCEROS = '10';
    case ARRENDAMIENTO_LOCAL = '11';
    case IVA_PENDIENTE_CERTIFICACIONES = '14';
    case IVA_PENDIENTE_TRACTOSUCESIVO = '15';
    case REGIMEN_CAPITULO_XI = '17';
    case RECARGO_EQUIVALENCIA = '18';
    case REAGYP = '19';
    case REGIMEN_SIMPLIFICADO = '20';

    public function descripcion(): string {
        return match($this) {
            self::REGIMEN_GENERAL => 'Operación de régimen general.',
            self::EXPORTACION => 'Exportación.',
            self::BIENES_USADOS => 'Operaciones a las que se aplique el régimen especial de bienes usados, objetos de arte, antigüedades y objetos de colección.',
            self::ORO_INVERSION => 'Régimen especial del oro de inversión.',
            self::AGENCIAS_VIAJES => 'Régimen especial de las agencias de viajes.',
            self::GRUPO_ENTIDADES => 'Régimen especial grupo de entidades en IVA (Nivel Avanzado).',
            self::CRITERIO_CAJA => 'Régimen especial del criterio de caja.',
            self::IPSI_IGIC => 'Operaciones sujetas al IPSI / IGIC (Impuesto sobre la Producción, los Servicios y la Importación / Impuesto General Indirecto Canario).',
            self::AGENCIAS_VIAJE_MEDIADORAS => 'Facturación de las prestaciones de servicios de agencias de viaje que actúan como mediadoras en nombre y por cuenta ajena (D.A.4ª RD1619/2012).',
            self::COBROS_TERCEROS => 'Cobros por cuenta de terceros de honorarios profesionales o de derechos derivados de la propiedad industrial, de autor u otros por cuenta de sus socios, asociados o colegiados efectuados por sociedades, asociaciones, colegios profesionales u otras entidades que realicen estas funciones de cobro.',
            self::ARRENDAMIENTO_LOCAL => 'Operaciones de arrendamiento de local de negocio.',
            self::IVA_PENDIENTE_CERTIFICACIONES => 'Factura con IVA pendiente de devengo en certificaciones de obra cuyo destinatario sea una Administración Pública.',
            self::IVA_PENDIENTE_TRACTOSUCESIVO => 'Factura con IVA pendiente de devengo en operaciones de tracto sucesivo.',
            self::REGIMEN_CAPITULO_XI => 'Operación acogida a alguno de los regímenes previstos en el Capítulo XI del Título IX (OSS e IOSS).',
            self::RECARGO_EQUIVALENCIA => 'Recargo de equivalencia.',
            self::REAGYP => 'Operaciones de actividades incluidas en el Régimen Especial de Agricultura, Ganadería y Pesca (REAGYP).',
            self::REGIMEN_SIMPLIFICADO => 'Régimen simplificado.',
        };
    }
}

enum L8B: string {
    case REGIMEN_GENERAL = '01';
    case EXPORTACION = '02';
    case BIENES_USADOS = '03';
    case ORO_INVERSION = '04';
    case AGENCIAS_VIAJES = '05';
    case GRUPO_ENTIDADES = '06';
    case CRITERIO_CAJA = '07';
    case IPSI_IVA = '08';
    case AGENCIAS_VIAJE_MEDIADORAS = '09';
    case COBROS_TERCEROS = '10';
    case ARRENDAMIENTO_LOCAL = '11';
    case IGIC_PENDIENTE_CERTIFICACIONES = '14';
    case IGIC_PENDIENTE_TRACTOSUCESIVO = '15';
    case COMERCIANTE_MINORISTA = '17';
    case PEQUENO_EMPRESARIO = '18';
    case EXENTAS_ARTICULO_25 = '19';

    public function description(): string {
        return match($this) {
            self::REGIMEN_GENERAL => 'Operación de régimen general.',
            self::EXPORTACION => 'Exportación.',
            self::BIENES_USADOS => 'Operaciones a las que se aplique el régimen especial de bienes usados, objetos de arte, antigüedades y objetos de colección.',
            self::ORO_INVERSION => 'Régimen especial del oro de inversión.',
            self::AGENCIAS_VIAJES => 'Régimen especial de las agencias de viajes.',
            self::GRUPO_ENTIDADES => 'Régimen especial grupo de entidades en IGIC (Nivel Avanzado).',
            self::CRITERIO_CAJA => 'Régimen especial del criterio de caja.',
            self::IPSI_IVA => 'Operaciones sujetas al IPSI / IVA (Impuesto sobre la Producción, los Servicios y la Importación / Impuesto sobre el Valor Añadido).',
            self::AGENCIAS_VIAJE_MEDIADORAS => 'Facturación de las prestaciones de servicios de agencias de viaje que actúan como mediadoras en nombre y por cuenta ajena (D.A.4ª RD1619/2012).',
            self::COBROS_TERCEROS => 'Cobros por cuenta de terceros de honorarios profesionales o de derechos derivados de la propiedad industrial, de autor u otros por cuenta de sus socios, asociados o colegiados efectuados por sociedades, asociaciones, colegios profesionales u otras entidades que realicen estas funciones de cobro.',
            self::ARRENDAMIENTO_LOCAL => 'Operaciones de arrendamiento de local de negocio.',
            self::IGIC_PENDIENTE_CERTIFICACIONES => 'Factura con IGIC pendiente de devengo en certificaciones de obra cuyo destinatario sea una Administración Pública.',
            self::IGIC_PENDIENTE_TRACTOSUCESIVO => 'Factura con IGIC pendiente de devengo en operaciones de tracto sucesivo.',
            self::COMERCIANTE_MINORISTA => 'Régimen especial de comerciante minorista.',
            self::PEQUENO_EMPRESARIO => 'Régimen especial del pequeño empresario o profesional.',
            self::EXENTAS_ARTICULO_25 => 'Operaciones interiores exentas por aplicación artículo 25 Ley 19/1994.',
        };
    }
}