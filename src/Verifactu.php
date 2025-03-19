<?php
namespace jdg\Verifactu;

use jdg\Verifactu\InvoiceHasher;

class Verifactu {
    private $wsdl;
    private $schemaBaseUrl;

    public function __construct($production = false) {
        if ($production) {
            $wsdl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl';
            $schemaBaseUrl = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/';
        } else {
            $wsdl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl';
            $schemaBaseUrl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/';
        }
        echo "Verifactu class loaded";
    }

    public function registroFactura($registroFactura) {
        $ret = ['xml'=>'','response'=>'','status'=>''];

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        // Definir namespaces
        $soapNs = 'http://schemas.xmlsoap.org/soap/envelope/';
        $sumNs = $this->schemaBaseUrl.'SuministroLR.xsd';
        $sum1Ns = $this->schemaBaseUrl.'SuministroInformacion.xsd';
        $xdNs = 'http://www.w3.org/2000/09/xmldsig#';
        
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
        

        $registroFactura= [
            'Cabecera' => [
                'ObligadoEmision' => [
                    'NombreRazon' => 'XXXXX',
                    'NIF' => 'AAAA'
                ]
            ],
            'RegistroFactura' => [
                'RegistroAlta' => [
                    'IDVersion' => '1.0',
                    'IDFactura' => [
                        'IDEmisorFactura' => 'AAAA',
                        'NumSerieFactura' => '12345',
                        'FechaExpedicionFactura' => '13-09-2024',
                    ],
                    'NombreRazonEmisor' => 'XXXXXX',
                    'TipoFactura' => 'XXXXXX',
                    'DescripcionOperacion' => 'XXXXXX',
                    'Destinatarios' => [
                        [
                            'NombreRazon' => 'YYYY',
                            'NIF' => 'BBBB'
                        ]
                    ]
                ]
            ]
            
        ];

        // Cabecera
        $cabecera = $dom->createElementNS($sumNs, 'sum:Cabecera');
        $obligado = $dom->createElementNS($sum1Ns, 'sum1:ObligadoEmision');
        $obligado->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazon', $registroFactura['cabecera']['ObligadoEmision']['NombreRazon']));
        $obligado->appendChild($dom->createElementNS($sum1Ns, 'sum1:NIF', $registroFactura['cabecera']['ObligadoEmision']['NIF']));
        $cabecera->appendChild($obligado);
        $regFactu->appendChild($cabecera);
        
        // RegistroFactura
        $registroFactura = $dom->createElementNS($sumNs, 'sum:RegistroFactura');
        $regAlta = $dom->createElementNS($sum1Ns, 'sum1:RegistroAlta');
        
        // ID Version
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:IDVersion', $registroFactura['RegistroFactura']['RegistroAlta']['IDVersion']));
        
        // IDFactura
        $idFactura = $dom->createElementNS($sum1Ns, 'sum1:IDFactura');
        $idFactura->appendChild($dom->createElementNS($sum1Ns, 'sum1:IDEmisorFactura', $registroFactura['RegistroFactura']['RegistroAlta']['IDFactura']['IDEmisorFactura']));
        $idFactura->appendChild($dom->createElementNS($sum1Ns, 'sum1:NumSerieFactura', $registroFactura['RegistroFactura']['RegistroAlta']['IDFactura']['NumSerieFactura']));
        $idFactura->appendChild($dom->createElementNS($sum1Ns, 'sum1:FechaExpedicionFactura', $registroFactura['RegistroFactura']['RegistroAlta']['IDFactura']['FechaExpedicionFactura']));
        $regAlta->appendChild($idFactura);
        
        // Resto de elementos
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazonEmisor', $registroFactura['RegistroFactura']['RegistroAlta']['NombreRazonEmisor']));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoFactura', $registroFactura['RegistroFactura']['RegistroAlta']['TipoFactura']));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:DescripcionOperacion', $registroFactura['RegistroFactura']['RegistroAlta']['DescripcionOperacion']));
        
        // Destinatarios
        $destinatarios = $dom->createElementNS($sum1Ns, 'sum1:Destinatarios');
        foreach($registroFactura['RegistroFactura']['RegistroAlta']['Destinatarios'] as $destinatario) {
            $idDestinatario = $dom->createElementNS($sum1Ns, 'sum1:IDDestinatario');
            $idDestinatario->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazon', $destinatario['NombreRazon']));
            $idDestinatario->appendChild($dom->createElementNS($sum1Ns, 'sum1:NIF', $destinatario['NIF']));
            $destinatarios->appendChild($idDestinatario);
        }
        $regAlta->appendChild($destinatarios);
        
        // Desglose (2 DetalleDesglose)
        $desglose = $dom->createElementNS($sum1Ns, 'sum1:Desglose');
        
        // Primer DetalleDesglose
        $detalle1 = $dom->createElementNS($sum1Ns, 'sum1:DetalleDesglose');
        $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:ClaveRegimen', '01'));
        $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:CalificacionOperacion', 'S1'));
        $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoImpositivo', '4'));
        $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:BaseImponibleOimporteNoSujeto', '10'));
        $detalle1->appendChild($dom->createElementNS($sum1Ns, 'sum1:CuotaRepercutida', '0.4'));
        $desglose->appendChild($detalle1);
        
        // Segundo DetalleDesglose
        $detalle2 = $dom->createElementNS($sum1Ns, 'sum1:DetalleDesglose');
        $detalle2->appendChild($dom->createElementNS($sum1Ns, 'sum1:ClaveRegimen', '01'));
        $detalle2->appendChild($dom->createElementNS($sum1Ns, 'sum1:CalificacionOperacion', 'S1'));
        $detalle2->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoImpositivo', '21'));
        $detalle2->appendChild($dom->createElementNS($sum1Ns, 'sum1:BaseImponibleOimporteNoSujeto', '100'));
        $detalle2->appendChild($dom->createElementNS($sum1Ns, 'sum1:CuotaRepercutida', '21'));
        $desglose->appendChild($detalle2);
        
        $regAlta->appendChild($desglose);
        
        // Cuotas e Importes
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:CuotaTotal', '21.4'));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:ImporteTotal', '131.4'));
        
        // Encadenamiento
        $encadenamiento = $dom->createElementNS($sum1Ns, 'sum1:Encadenamiento');
        $registroAnterior = $dom->createElementNS($sum1Ns, 'sum1:RegistroAnterior');
        $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:IDEmisorFactura', 'AAAA'));
        $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:NumSerieFactura', '44'));
        $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:FechaExpedicionFactura', '13-09-2024'));
        $registroAnterior->appendChild($dom->createElementNS($sum1Ns, 'sum1:Huella', 'HuellaRegistroAnterior'));
        $encadenamiento->appendChild($registroAnterior);
        $regAlta->appendChild($encadenamiento);
        
        // SistemaInformatico
        $sistemaInfo = $dom->createElementNS($sum1Ns, 'sum1:SistemaInformatico');
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreRazon', 'SSSS'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NIF', 'NNNN'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NombreSistemaInformatico', 'NombreSistemaInformatico'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:IdSistemaInformatico', '77'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:Version', '1.0.03'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:NumeroInstalacion', '383'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoUsoPosibleSoloVerifactu', 'N'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoUsoPosibleMultiOT', 'S'));
        $sistemaInfo->appendChild($dom->createElementNS($sum1Ns, 'sum1:IndicadorMultiplesOT', 'S'));
        $regAlta->appendChild($sistemaInfo);
        
        // Elementos finales
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:FechaHoraHusoGenRegistro', '2024-09-13T19:20:30+01:00'));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:TipoHuella', '01'));
        $regAlta->appendChild($dom->createElementNS($sum1Ns, 'sum1:Huella', 'Huella'));
        
        // Ensamblar todo
        $registroFactura->appendChild($regAlta);
        $regFactu->appendChild($registroFactura);
        
        // Validar y mostrar XML
        if (!$dom->schemaValidate('xsd/SuministroLR.xsd')) {
            $ret['status'] = 'fail';
            $ret['response'] = 'XML no cumple con el esquema XSD';
        }
        $xmlString = $dom->saveXML();
        $ret['xml'] = $xmlString;
        
        // ========== ENVÍO SOAP ==========
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
                new \SoapVar($xmlString, XSD_ANYXML)
            ]);
            $ret['response'] = $client->__getLastResponse();
            $ret['status'] = 'sent';
        } catch (\SoapFault $e) {
            $ret['response'] = $e->getMessage()."\n ---------------------------- \n".$client->__getLastRequest();
            $ret['status'] = 'fail';
        }        
        return $ret;
    }
}