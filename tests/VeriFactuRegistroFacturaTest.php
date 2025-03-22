<?php
use PHPUnit\Framework\TestCase;
use jdg\Verifactu\VeriFactuRegistroFactura;
use jdg\Verifactu\Listas;

class VeriFactuRegistroFacturaTest extends TestCase
{
    /**
     * Test generating the hash with valid invoice data.
     */
    public function testCreateXml()
    {
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
                        ]
                    ],
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
        $verifactu = new VeriFactuRegistroFactura();
        $ret = $verifactu->createXml($registroFactura);
        /*
        echo '-------------------------'."\n";
        echo $ret['details'];
        echo "\n".'-------------------------'."\n";
        echo $ret['xml'];
        echo "\n".'-------------------------'."\n";
        */
        if ($ret['error']!='') {
            echo "\n-----------\n".$ret['details']."\n-----------\n";
        }
        $xml = file_get_contents(__DIR__.'/testCreateXmlRegistroFacturaPrimerRegistro.xml');
        $this->assertFalse($xml===false, 'Error reading expected xml: '.print_r(error_get_last(),true));
        $this->assertEquals('', $ret['error']);
        $this->assertEquals($xml, $ret['xml']);
        $this->assertEquals($hash, $ret['hash']);
        $this->assertEquals($timestampISO8601, $ret['timestampISO8601']);
    }
}