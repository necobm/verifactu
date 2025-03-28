<?php

namespace jdg\Verifactu;

use jdg\Verifactu\VeriFactuDateTimeHelper;
use jdg\Verifactu\VeriFactuStringHelper;
use jdg\Verifactu\Listas;
use jdg\Verifactu\Models;

use function PHPUnit\Framework\throwException;

class VeriFactuRegistroFactura
{
    private $wsdl;
    private $schemaBaseUrl;
    private $location;

    public function __construct($production = false)
    {
        if ($production) {
            $this->wsdl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl';
            $this->schemaBaseUrl = 'https://www1.agenciatributaria.gob.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';
            // ToDo :: Extract the location from the WSDL
            $this->location = 'https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';
        } else {
            $this->wsdl = 'https://prewww2.aeat.es/static_files/common/internet/dep/aplicaciones/es/aeat/tikeV1.0/cont/ws/SistemaFacturacion.wsdl';
            $this->schemaBaseUrl = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/';
            $this->location = 'https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';
        }
    }

    public function send(Models\DsRegistroVeriFactu $dsRegistroVeriFactu, $certificatePath, $certificatePassphrase): array
    {
        $ret = ['request' => '', 'response' => '', 'status' => ''];

        try {
            $dsRegistroVeriFactuAsArray = $this->getAsArrayDsRegistroVeriFactu($dsRegistroVeriFactu);
        } catch(\Exception $e) {
            $ret['status'] = 'fail';
            $ret['response'] = $e->getMessage();
        }

        $ret['hashes'] = [];
        foreach($dsRegistroVeriFactuAsArray['RegistroFactura'] as $registroFactura) {
            if (isset($registroFactura['RegistroAlta'])) {
                $ret['hashes'] = [
                    'NumSerieFactura' => $registroFactura['RegistroAlta']['IDFactura']['NumSerieFactura'],
                    'Huella' => $registroFactura['RegistroAlta']['Huella'],
                    'FechaHoraHusoGenRegistro' => $registroFactura['RegistroAlta']['FechaHoraHusoGenRegistro']
                ];
            } else 
            if (isset($registroFactura['RegistroAnulacion'])) {
                $ret['hashes'] = [
                    'NumSerieFactura' => $registroFactura['RegistroAnulacion']['IDFactura']['NumSerieFactura'],
                    'Huella' => $registroFactura['RegistroAnulacion']['Huella'],
                    'FechaHoraHusoGenRegistro' => $registroFactura['RegistroAnulacion']['FechaHoraHusoGenRegistro']
                ];
            } else {
                throw new \Exception('The data contains a non valid "RegistroFactura" type.');
            } 
        }

        $options = [
            'local_cert' => $certificatePath,
            'passphrase' => $certificatePassphrase,
            'trace' => true,
            'exceptions' => true,
            'cache_wsdl' => 0, // WSDL_CACHE_NONE,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                    'allow_self_signed' => false,
                    'crypto_method' => 33, // STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                ],
            ]),
            'soap_version' => SOAP_1_1,
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL
        ];

//      $client = new SoapClientDebugger($this->wsdl, $options);
        $client = new \SoapClient($this->wsdl, $options);
        try {
            $client->__setLocation($this->location);
            $client->__soapCall('RegFactuSistemaFacturacion', [$dsRegistroVeriFactuAsArray]);
            $ret['request'] = $client->__getLastRequest();
            $ret['response'] = $client->__getLastResponse();
            $ret['status'] = 'sent';
        } catch (\SoapFault $e) {
            $ret['request'] = $client->__getLastRequest();
            $lastResponse = $client->__getLastResponse();
            if ( strpos($lastResponse, 'No se detecta certificado electr')!==false ) {
                $ret['response'] = 'No se detecta certificado electrónico';
            } else {
                $ret['response'] = $e->getMessage();
            }
            $ret['status'] = 'fail';
        }
        return $ret;
    } 

    private function getAsArrayRegistroAnulacion(Models\RegistroAnulacion $registroAnulacion):array {
        throw new \Exception('Not implemented');
    }

    private function getAsArrayRegistroAlta(string $obligadoEmisionNif, Models\RegistroAlta $registroAlta):array {
        $data = [
                'IDVersion' => $registroAlta->IDVersion->value,
                'IDFactura' => [
                    'IDEmisorFactura' => $registroAlta->IDFactura->IDEmisorFactura,
                    'NumSerieFactura' => $registroAlta->IDFactura->NumSerieFactura,
                    'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($registroAlta->IDFactura->FechaExpedicionFactura)
                ],
                'NombreRazonEmisor' => VeriFactuStringHelper::sanitizeString($registroAlta->NombreRazonEmisor),
                'TipoFactura' => $registroAlta->TipoFactura->value,
                'DescripcionOperacion' => VeriFactuStringHelper::sanitizeString($registroAlta->DescripcionOperacion),
                'Destinatarios' => [],
                'Desglose' => [],
            ];

        foreach ($registroAlta->Destinatarios as $destinatario) {
            $data['Destinatarios'][] = [
                'NombreRazon' => VeriFactuStringHelper::sanitizeString($destinatario->NombreRazon),
                'NIF' => VeriFactuStringHelper::sanitizeString($destinatario->NIF)
            ];
        }
        

        // Desglose (DetalleDesglose)
        $cuotaTotal = 0.0;
        $baseImponibleTotal = 0.0;
        foreach ($registroAlta->Desgloses as $detalleDesglose) {
            $data['Desglose'][] = [
                    'ClaveRegimen' => $detalleDesglose->ClaveRegimen->value,
                    'CalificacionOperacion' => $detalleDesglose->CalificacionOperacion->value,
                    'TipoImpositivo' => $detalleDesglose->TipoImpositivo,
                    'BaseImponibleOimporteNoSujeto' => $detalleDesglose->BaseImponibleOimporteNoSujeto,
                    'CuotaRepercutida' => $detalleDesglose->CuotaRepercutida
            ];

            $cuotaTotal = bcadd($cuotaTotal, $detalleDesglose->CuotaRepercutida, 2);
            if ($detalleDesglose->ClaveRegimen != Listas\L8A::RECARGO_EQUIVALENCIA) {
                $baseImponibleTotal = bcadd($baseImponibleTotal, $detalleDesglose->BaseImponibleOimporteNoSujeto, 2);
            }
        }

        // Cuotas e Importes
        $importeTotal = bcadd($cuotaTotal, $baseImponibleTotal, 2);

        if (isset($registroAlta->CuotaTotal)) {
            $expected = bcadd($registroAlta->CuotaTotal, 0, 2);
            if ($expected != $cuotaTotal) {
                throw new \Exception('La suma de cuotas en los desgloses no coincide con la cuota total.');
            }
        }
        $expected = bcadd($registroAlta->ImporteTotal, 0, 2);
        if ($expected != $importeTotal) {
            throw new \Exception('La suma de importes en los desgloses no coincide con el importe total.');
        }
        $data['CuotaTotal'] = $cuotaTotal;
        $data['ImporteTotal'] = $importeTotal;

        // Encadenamiento
        $huellaAnterior = '';
        if ($registroAlta->Encadenamiento->PrimerRegistro) {
            $data['Encadenamiento'] = [
                'PrimerRegistro' => 'S'
            ];
        } else {
            $huellaAnterior = $registroAlta->Encadenamiento->RegistroAnterior->Huella;
            $data['Encadenamiento'] = [
                'RegistroAnterior' => [
                    'IDEmisorFactura' => $registroAlta->Encadenamiento->RegistroAnterior->IDEmisorFactura,
                    'NumSerieFactura' => $registroAlta->Encadenamiento->RegistroAnterior->NumSerieFactura,
                    'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($registroAlta->Encadenamiento->RegistroAnterior->FechaExpedicionFactura),
                    'Huella' => $huellaAnterior
                ]
            ];
        }

        // SistemaInformatico
        $data['SistemaInformatico'] = [
            'NombreRazon' => $registroAlta->SistemaInformatico->NombreRazon,
            'NIF' => $registroAlta->SistemaInformatico->NIF,
            'NombreSistemaInformatico' => $registroAlta->SistemaInformatico->NombreSistemaInformatico,
            'IdSistemaInformatico' => $registroAlta->SistemaInformatico->IdSistemaInformatico,
            'Version' => $registroAlta->SistemaInformatico->Version,
            'NumeroInstalacion' => $registroAlta->SistemaInformatico->NumeroInstalacion,
            'TipoUsoPosibleSoloVerifactu' => $registroAlta->SistemaInformatico->TipoUsoPosibleSoloVerifactu->value,
            'TipoUsoPosibleMultiOT' => $registroAlta->SistemaInformatico->TipoUsoPosibleMultiOT->value,
            'IndicadorMultiplesOT' => $registroAlta->SistemaInformatico->IndicadorMultiplesOT->value
        ];

        $huella = '';
        $verifactuISO8601CreationTime = '';
        if ($registroAlta->FechaHoraHusoGenRegistro!=null && $registroAlta->Huella!=null) {
            $huella = $registroAlta->Huella;
            $verifactuISO8601CreationTime = $registroAlta->FechaHoraHusoGenRegistro;
        } else {
            $verifactuISO8601CreationTime = VeriFactuDateTimeHelper::nowIso8601();
            $invoiceData = [
                'IDEmisorFactura' => $obligadoEmisionNif,
                'NumSerieFactura' => $registroAlta->IDFactura->NumSerieFactura,
                'FechaExpedicionFactura' => VeriFactuDateTimeHelper::formatDate($registroAlta->IDFactura->FechaExpedicionFactura),
                'TipoFactura' => $registroAlta->TipoFactura->value,
                'CuotaTotal' => $cuotaTotal,
                'ImporteTotal' => $importeTotal,
                'Huella' => $huellaAnterior,
                'FechaHoraHusoGenRegistro' => $verifactuISO8601CreationTime,
            ];
            try {
                $hashInvoice = VeriFactuHashGenerator::generateHashInvoice($invoiceData);
                $huella = $hashInvoice['hash'];
            } catch (\Exception $e) {
                return ['error' => 'Error creataing hash', 'details' => $e->getMessage()];
            }
        }

        // Elementos finales
        $data['FechaHoraHusoGenRegistro'] =  $verifactuISO8601CreationTime;
        $data['TipoHuella'] =  Listas\L12::SHA256->value;
        $data['Huella'] =  $huella; 
        return $data;
    }

    private function getAsArrayDsRegistroVeriFactu(Models\DsRegistroVeriFactu $dsRegistroVeriFactu): array
    {
        $obligadoEmisionNif = $dsRegistroVeriFactu->Cabecera->ObligadoEmision->NIF;
        $data = [
            'Cabecera' => [
                'ObligadoEmision'=>[
                    'NombreRazon' => VeriFactuStringHelper::sanitizeString($dsRegistroVeriFactu->Cabecera->ObligadoEmision->NombreRazon),
                    'NIF' => VeriFactuStringHelper::sanitizeString($obligadoEmisionNif)
                ]
            ],
            'RegistroFactura' => []
        ];

        /*
        * @var Models\RegistroAnterior
        */
        $registroAnterior = null;
        foreach($dsRegistroVeriFactu->RegistroFactura as $registroFactura) {
            if ($registroFactura->RegistroAlta!=null) {
                if ($registroAnterior!=null) {
                    $registroFactura->RegistroAlta->Encadenamiento->RegistroAnterior = $registroAnterior;
                }
                $data['RegistroFactura'][] = ['RegistroAlta' => $this->getAsArrayRegistroAlta($obligadoEmisionNif, $registroFactura->RegistroAlta)];
                $registroAnterior = new Models\RegistroAnterior(
                    $obligadoEmisionNif,
                    $registroFactura->RegistroAlta->IDFactura->NumSerieFactura,
                    $registroFactura->RegistroAlta->IDFactura->FechaExpedicionFactura,
                    $registroFactura->RegistroAlta->Huella
                );
            } else
            if ($registroFactura->RegistroAnulacion!=null) {
                if ($registroAnterior!=null) {
                    $registroFactura->RegistroAnulacion->Encadenamiento->RegistroAnterior = $registroAnterior;
                }
                $data['RegistroFactura'][] = ['RegistroAnulacion' => $this->getAsArrayRegistroAnulacion($registroFactura->RegistroAnulacion)];
                $registroAnterior = new Models\RegistroAnterior(
                    $obligadoEmisionNif,
                    'ToDo', //$registroFactura->RegistroAnulacion->IDFactura->NumSerieFactura,
                    'ToDo', //$registroFactura->RegistroAnulacion->IDFactura->FechaExpedicionFactura,
                    $registroFactura->RegistroAnulacion->Huella
                );
            }
        }
        return $data;
    }
/*
[response] => looks like we got no XML document
[response] => SOAP-ERROR: Encoding: object has no 'Cabecera' property
[response] => No se detecta certificado electrónico
[response] => Codigo[103].NIF no identificado: 99999972C/EIDAS CERTIFICADO PRUEBAS
*/
}
