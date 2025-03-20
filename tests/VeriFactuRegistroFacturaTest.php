<?php
use PHPUnit\Framework\TestCase;
use jdg\Verifactu\VeriFactuRegistroFactura;

class VeriFactuRegistroFacturaTest extends TestCase
{
    /**
     * Test generating the hash with valid invoice data.
     */
    public function testCreateXml()
    {
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
                    ],
                    'Desgloses' => [
                        [
                            'ClaveRegimen'=>'01',
                            'CalificacionOperacion'=>'S1',
                            'TipoImpositivo'=>'4',
                            'BaseImponibleOimporteNoSujeto'=>'10',
                            'CuotaRepercutida'=>'0.4',

                        ], 
                        [
                            'ClaveRegimen'=>'01',
                            'CalificacionOperacion'=>'S1',
                            'TipoImpositivo'=>'21',
                            'BaseImponibleOimporteNoSujeto'=>'100',
                            'CuotaRepercutida'=>'21',
                        ]
                    ],
                    'Encadenamiento' => [
                        'RegistroAnterior' => [
                            'IDEmisorFactura'=> 'AAAA',    
                            'NumSerieFactura'=> '44',    
                            'FechaExpedicionFactura'=> '13-09-2024',    
                            'Huella'=> 'HuellaRegistroAnterior',    
                        ]
                    ],
                    'SistemaInformatico' => [
                        'NombreRazon'=> 'SSSS',    
                        'NIF'=> 'NNNN',    
                        'NombreSistemaInformatico'=> 'NombreSistemaInformatico',    
                        'IdSistemaInformatico'=> '77',    
                        'Version'=> '1.0.03',    
                        'NumeroInstalacion'=> '383',    
                        'TipoUsoPosibleSoloVerifactu'=> 'N',    
                        'TipoUsoPosibleMultiOT'=> 'S',    
                        'IndicadorMultiplesOT'=> 'S',    
                    ],

                ]
            ]
            
        ];
        $verifactu = new VeriFactuRegistroFactura();
        $ret = $verifactu->createXml($registroFactura);
        echo '-------------------------'."\n";
        echo $ret['details'];
        echo "\n".'-------------------------'."\n";
        echo $ret['xml'];
        echo "\n".'-------------------------'."\n";
        $this->assertEquals($ret['error'], '');
        $this->assertEquals($ret['xml'], file_get_contents('testCreateXmlRegistroFacturaPrimerRegistro.xml'));
    }
}