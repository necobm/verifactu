<?php
use jdg\Verifactu\Listas;

require 'SoapClientDebugger.php';
require 'VeriFactuRegistroFactura.php';
require 'VeriFactuStringHelper.php';
require 'VeriFactuDateTimeHelper.php';
require 'Listas/L15.php';
require 'Listas/L12.php';
require 'Listas/L2.php';
require 'Listas/L8A.php';
require 'Listas/L9.php';
require 'Listas/L4.php';

function sendEnvelope() {
    $hash = '73238806452AEAD89CC3A97DDAF2F5BA986BBC7957CF30E8C888B2F9EBE228A0';
    $timestampISO8601 = '2025-03-22T13:10:37+00:00';
    $registroFactura= [
        'Cabecera' => [
            'ObligadoEmision' => [
                'NombreRazon' => 'Obligado S.L.',
                'NIF' => 'K1111111I'
            ]
        ],
        'RegistroFactura' => [
            'RegistroAlta' => [
                'IDVersion' => Listas\L15::V1->value,
                'IDFactura' => [
                    'IDEmisorFactura' => 'K1111111I',
                    'NumSerieFactura' => 'A/2025012345',
                    'FechaExpedicionFactura' => '22-03-2025',//date('Y-m-d'),
                ],
                'NombreRazonEmisor' => 'Obligado S.L.',
                'TipoFactura' => Listas\L2::F1->value,
                'DescripcionOperacion' => 'Factura de venta',
                'Destinatarios' => [
                    [
                        'NombreRazon' => 'Cliente Físico',
                        'NIF' => 'Z5432106V'
                    ]
                ],
                'Desgloses' => [
                    [
                        'ClaveRegimen'=>Listas\L8A::REGIMEN_GENERAL->value,
                        'CalificacionOperacion'=>Listas\L9::S1->value,
                        'TipoImpositivo'=>'4',
                        'BaseImponibleOimporteNoSujeto'=>'10',
                        'CuotaRepercutida'=>'0.4',
                    ], 
                    [
                        'ClaveRegimen'=>Listas\L8A::REGIMEN_GENERAL->value,
                        'CalificacionOperacion'=>Listas\L9::S1->value,
                        'TipoImpositivo'=>'21',
                        'BaseImponibleOimporteNoSujeto'=>'100',
                        'CuotaRepercutida'=>'21',
                    ],
                    // ToDo :: Improve the test, adding "recargo de equivalencia"
                ],
                'CuotaTotal'=>'21.40',
                'ImporteTotal'=>'131.40',
                'Encadenamiento' => [
                    'RegistroAnterior' => [
                        'IDEmisorFactura'=> 'K1111111I',    
                        'NumSerieFactura'=> 'A/2025012344',    
                        'FechaExpedicionFactura'=> '22-03-2025',//date('Y-m-d'),    
                        'Huella'=> '3C464DAF61ACB827C65FDA19F352A4E3BDC2C640E9E9FC4CC058073F38F12F60',    
                    ]
                ],
                'SistemaInformatico' => [
                    'NombreRazon'=> '11111111H',    
                    'NIF'=> '11111111H',    
                    'NombreSistemaInformatico'=> 'Invoice Verifactu API',    
                    'IdSistemaInformatico'=> '01',    
                    'Version'=> '1.0.0',   
                    'NumeroInstalacion'=> 'company-01',    
                    'TipoUsoPosibleSoloVerifactu'=> Listas\L4::SI->value,    
                    'TipoUsoPosibleMultiOT'=> Listas\L4::NO->value,    
                    'IndicadorMultiplesOT'=> Listas\L4::NO->value,
                ],
                // For test
                'Huella' => $hash,
                'FechaHoraHusoGenRegistro' => $timestampISO8601,
            ]
        ]
    ];

    $certificatePath = '/path/to/your/certificate.pem';
    $passphrase = 'your_certificate_passphrase';

    $verifactu = new jdg\Verifactu\VeriFactuRegistroFactura();
    $data = $verifactu->createDsRegistroVeriFactu($registroFactura);

    $ret = $verifactu->send($data['param'], $certificatePath, $passphrase);
    $passphrase = 'your_certificate_passphrase';

    echo "\n -----------------";
    print_r($ret);
}

// https://es.stackoverflow.com/questions/81686/enviar-xml-al-web-service-de-sii-aeat-php-soap
// https://www.aeatsiidesarrolladores.es/
function showMethods() {
    $wsdl = "https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl";
    try {
        $client = new SoapClient($wsdl, ['trace' => 1, 'exceptions' => 1]);
    
        // Obtener y mostrar los métodos disponibles
        print_r($client->__getFunctions());
    } catch (SoapFault $e) {
        echo "Error al obtener métodos: " . $e->getMessage();
    }    
}

// showMethods();
sendEnvelope();

/*
[response] => looks like we got no XML document
*/