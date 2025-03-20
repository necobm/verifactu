<?php
namespace jdg\Verifactu;

use jdg\Verifactu\VeriFactuDateTimeHelper;
use jdg\Verifactu\VeriFactuStringHelper;
use jdg\Verifactu\Listas\L12;

class VeriFactuRegistroFactura {
    private $wsdl;
    private $schemaBaseUrl;

    public function __construct($production = false) {
        if ($production) {
            $this->wsdl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl';
            $this->schemaBaseUrl = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/';
        } else {
            $this->wsdl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl';
            $this->schemaBaseUrl = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/';
        }
    }

    public function sendXml(string $xml):array {
        $ret = ['response'=>'','status'=>''];

        $options = [
            'trace' => 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'ciphers' => 'DEFAULT:!DH'
                ]
            ])
        ];
        
        $client = new \SoapClient($this->wsdl, $options);
        $client->__setSoapHeaders(/* Agrega cabeceras si son necesarias */);
        
        try {
            $response = $client->__soapCall('SuministroFacturacion', [
                new \SoapVar($xml, XSD_ANYXML)
            ]);
            $ret['response'] = $client->__getLastResponse();
            $ret['status'] = 'sent';
        } catch (\SoapFault $e) {
            $ret['response'] = $e->getMessage()."\n ---------------------------- \n".$client->__getLastRequest();
            $ret['status'] = 'fail';
        }        
        return $ret;
    }

    public function createXml($invoiceRecord):array {
        $ret = ['xml'=>'', 'error'=>''];

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        // Definir namespaces
        $sumNs = $this->schemaBaseUrl.'SuministroLR.xsd';
        $sum1Ns = $this->schemaBaseUrl.'SuministroInformacion.xsd';
        $xdNs = 'http://www.w3.org/2000/09/xmldsig#';
        
        /*
        $soapNs = 'http://schemas.xmlsoap.org/soap/envelope/';
        // Crear elemento raíz SOAP
        $envelope = $dom->createElementNS($soapNs, 'soapenv:Envelope');
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sum', $sumNs);
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sum1', $sum1Ns);
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xd', $xdNs);
        $dom->appendChild($envelope);
        
        // Crear Body
        $body = $dom->createElementNS($soapNs, 'soapenv:Body');
        $envelope->appendChild($body);
        
        // RegFactuSistemaFacturacion
        $regFactu = $dom->createElementNS($sumNs, 'sum:RegFactuSistemaFacturacion');
        $body->appendChild($regFactu);
        */
        $regFactu = $dom->createElementNS($sumNs, 'sum:RegFactuSistemaFacturacion');
        $regFactu->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sum', $sumNs);
        $regFactu->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sum1', $sum1Ns);
        $regFactu->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xd', $xdNs);
        $dom->appendChild($regFactu);

        

        // Cabecera
        $cabecera = $dom->createElementNS($sumNs, 'sum:Cabecera');
        $obligado = $dom->createElementNS($sum1Ns, 'sum1:ObligadoEmision');
        $obligado->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazon', VeriFactuStringHelper::sanitizeString($invoiceRecord['Cabecera']['ObligadoEmision']['NombreRazon'])));
        $obligado->appendChild($dom->createElementNS($sum1Ns, 'sum1:NIF', $invoiceRecord['Cabecera']['ObligadoEmision']['NIF']));
        $cabecera->appendChild($obligado);
        $regFactu->appendChild($cabecera);
        
        // RegistroFactura
        $registroFactura = $dom->createElementNS($sumNs, 'sum:RegistroFactura');
        $regAlta = $dom->createElementNS($sum1Ns, 'sum1:RegistroAlta');
        
        // ID Version
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:IDVersion', $invoiceRecord['RegistroFactura']['RegistroAlta']['IDVersion']));
        
        // IDFactura
        $idFactura = $dom->createElementNS($sum1Ns, 'sum1:IDFactura');
        $idFactura->appendChild($dom->createElementNS($sum1Ns, 'sum1:IDEmisorFactura', $invoiceRecord['RegistroFactura']['RegistroAlta']['IDFactura']['IDEmisorFactura']));
        $idFactura->appendChild($dom->createElementNS($sum1Ns, 'sum1:NumSerieFactura', $invoiceRecord['RegistroFactura']['RegistroAlta']['IDFactura']['NumSerieFactura']));
        $idFactura->appendChild($dom->createElementNS($sum1Ns, 'sum1:FechaExpedicionFactura', VeriFactuDateTimeHelper::formatDate($invoiceRecord['RegistroFactura']['RegistroAlta']['IDFactura']['FechaExpedicionFactura'])));
        $regAlta->appendChild($idFactura);
        
        // Resto de elementos
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazonEmisor', VeriFactuStringHelper::sanitizeString($invoiceRecord['RegistroFactura']['RegistroAlta']['NombreRazonEmisor'])));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoFactura', $invoiceRecord['RegistroFactura']['RegistroAlta']['TipoFactura']));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:DescripcionOperacion', VeriFactuStringHelper::sanitizeString($invoiceRecord['RegistroFactura']['RegistroAlta']['DescripcionOperacion'])));
        
        // Destinatarios
        $destinatarios = $dom->createElementNS($sum1Ns, 'sum1:Destinatarios');
        foreach($invoiceRecord['RegistroFactura']['RegistroAlta']['Destinatarios'] as $destinatario) {
            $idDestinatario = $dom->createElementNS($sum1Ns, 'sum1:IDDestinatario');
            $idDestinatario->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazon', VeriFactuStringHelper::sanitizeString($destinatario['NombreRazon'])));
            $idDestinatario->appendChild($dom->createElementNS($sum1Ns, 'sum1:NIF', $destinatario['NIF']));
            $destinatarios->appendChild($idDestinatario);
        }
        $regAlta->appendChild($destinatarios);
        
        // Desglose (DetalleDesglose)
        $cuotaTotal = 0.0;
        $baseImponibleTotal = 0.0;
        $desglose = $dom->createElementNS($sum1Ns, 'sum1:Desglose');
        foreach($invoiceRecord['RegistroFactura']['RegistroAlta']['Desgloses'] as $detalleDesglose) {
            $detalle1 = $dom->createElementNS($sum1Ns, 'sum1:DetalleDesglose');
            $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:ClaveRegimen', $detalleDesglose['ClaveRegimen']));
            $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:CalificacionOperacion', $detalleDesglose['CalificacionOperacion']));
            $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoImpositivo', $detalleDesglose['TipoImpositivo']));
            $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:BaseImponibleOimporteNoSujeto', $detalleDesglose['BaseImponibleOimporteNoSujeto']));
            $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:CuotaRepercutida', $detalleDesglose['CuotaRepercutida']));
            $desglose->appendChild($detalle1);
            $cuotaTotal = bcadd($cuotaTotal, $detalleDesglose['CuotaRepercutida'], 2);
            $baseImponibleTotal = bcadd($baseImponibleTotal, $detalleDesglose['BaseImponibleOimporteNoSujeto'], 2);
        }
        $regAlta->appendChild($desglose);
        
        // Cuotas e Importes
        $importeTotal = bcadd($cuotaTotal, $baseImponibleTotal, 2);
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:CuotaTotal', $cuotaTotal));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:ImporteTotal', $importeTotal));
        
        // Encadenamiento
        $huellaAnterior = '';
        $encadenamiento = $dom->createElementNS($sum1Ns, 'sum1:Encadenamiento');
        if (!array_key_exists('Encadenamiento', $invoiceRecord['RegistroFactura']['RegistroAlta']) || !array_key_exists('RegistroAnterior', $invoiceRecord['RegistroFactura']['RegistroAlta']['Encadenamiento'])) {
            $primerRegistro = $dom->createElementNS($sum1Ns, 'sum1:PrimerRegistro', 'S');
            $encadenamiento->appendChild($primerRegistro);
        } else {
            $huellaAnterior = $invoiceRecord['RegistroFactura']['RegistroAlta']['Encadenamiento']['RegistroAnterior']['Huella'];
            $registroAnterior = $dom->createElementNS($sum1Ns, 'sum1:RegistroAnterior');
            $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:IDEmisorFactura', $invoiceRecord['RegistroFactura']['RegistroAlta']['Encadenamiento']['RegistroAnterior']['IDEmisorFactura']));
            $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:NumSerieFactura', $invoiceRecord['RegistroFactura']['RegistroAlta']['Encadenamiento']['RegistroAnterior']['NumSerieFactura']));
            $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:FechaExpedicionFactura', VeriFactuDateTimeHelper::formatDate($invoiceRecord['RegistroFactura']['RegistroAlta']['Encadenamiento']['RegistroAnterior']['FechaExpedicionFactura'])));
            $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:Huella', $huellaAnterior));
            $encadenamiento->appendChild($registroAnterior);
        }
        $regAlta->appendChild($encadenamiento);
        
        // SistemaInformatico
        $sistemaInfo = $dom->createElementNS($sum1Ns, 'sum1:SistemaInformatico');
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazon', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['NombreRazon']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NIF', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['NIF']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreSistemaInformatico', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['NombreSistemaInformatico']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:IdSistemaInformatico', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['IdSistemaInformatico']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:Version', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['Version']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NumeroInstalacion', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['NumeroInstalacion']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoUsoPosibleSoloVerifactu', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['TipoUsoPosibleSoloVerifactu']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoUsoPosibleMultiOT', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['TipoUsoPosibleMultiOT']));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:IndicadorMultiplesOT', $invoiceRecord['RegistroFactura']['RegistroAlta']['SistemaInformatico']['IndicadorMultiplesOT']));
        $regAlta->appendChild($sistemaInfo);

        $verifactuISO8601CreationTime = VeriFactuDateTimeHelper::nowIso8601();
        $invoiceData = [
            'IDEmisorFactura' => $invoiceRecord['Cabecera']['ObligadoEmision']['NIF'],
            'NumSerieFactura' => $invoiceRecord['RegistroFactura']['RegistroAlta']['IDFactura']['NumSerieFactura'],
            'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($invoiceRecord['RegistroFactura']['RegistroAlta']['IDFactura']['FechaExpedicionFactura']),
            'TipoFactura' => $invoiceRecord['RegistroFactura']['RegistroAlta']['TipoFactura'],
            'CuotaTotal' => $cuotaTotal,
            'ImporteTotal' => $importeTotal,
            'Huella' => $huellaAnterior,
            'FechaHoraHusoGenRegistro' => $verifactuISO8601CreationTime,
        ];
        list($huella, $cadenaHuella) = VeriFactuHashGenerator::generateHashInvoice($invoiceData);

        // Elementos finales
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:FechaHoraHusoGenRegistro', $verifactuISO8601CreationTime));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoHuella', L12::SHA256->value));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:Huella', $huella));
        
        // Ensamblar todo
        $registroFactura->appendChild($regAlta);
        $regFactu->appendChild($registroFactura);
        
        // Validar y mostrar XML
        libxml_use_internal_errors(true);
        if (!$dom->schemaValidate( __DIR__.'/xsd/SuministroLR.xsd' )) {
            $ret['error'] = 'Schema validation failed';
            $ret['details'] = $this->getXmlErrors();
        }
        libxml_clear_errors();

        $xmlString = $dom->saveXML();
        $ret['xml'] = $xmlString;
        $ret['hash'] = $huella; 
        $ret['timestampISO8601'] = $verifactuISO8601CreationTime;
        return $ret;
    }




    private function getXmlErrors() {
        $errors = libxml_get_errors();
        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[] = $this->formatLibXmlError($error);
        }

        return implode("\n", $errorMessages); // Join errors into a single string
    }

    private function formatLibXmlError($error) {
        $types = [
            LIBXML_ERR_WARNING => "Warning",
            LIBXML_ERR_ERROR => "Error",
            LIBXML_ERR_FATAL => "Fatal Error"
        ];
        
        return sprintf(
            "[%s] Line %d, Column %d: %s",
            $types[$error->level] ?? "Unknown",
            $error->line,
            $error->column,
            trim($error->message)
        );
    }    
}