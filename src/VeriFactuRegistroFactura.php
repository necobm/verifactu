<?php
namespace jdgOpenCode\verifactu;

use jdgOpenCode\verifactu\VeriFactuDateTimeHelper;
use jdgOpenCode\verifactu\VeriFactuStringHelper;
use jdgOpenCode\verifactu\Listas;
use jdgOpenCode\verifactu\Models;

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
            $dsRegistroVeriFactuAsArray = $dsRegistroVeriFactu->toArray();
        } catch(\Exception $e) {
            $ret['status'] = 'fail';
            $ret['response'] = $e->getMessage();
            return $ret;
        }
        $ret['hashes'] = [];
        foreach($dsRegistroVeriFactuAsArray['RegistroFactura'] as $registroFactura) {
            if (isset($registroFactura['RegistroAlta'])) {
                $ret['hashes'][] = [
                    'NumSerieFactura' => $registroFactura['RegistroAlta']['IDFactura']['NumSerieFactura'],
                    'Huella' => $registroFactura['RegistroAlta']['Huella'],
                    'FechaHoraHusoGenRegistro' => $registroFactura['RegistroAlta']['FechaHoraHusoGenRegistro']
                ];
            } else 
            if (isset($registroFactura['RegistroAnulacion'])) {
                $ret['hashes'][] = [
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
        try {
            $client = new \SoapClient($this->wsdl, $options);
            $client->__setLocation($this->location);
            $client->__soapCall('RegFactuSistemaFacturacion', [$dsRegistroVeriFactuAsArray]);
            $ret['request'] = $client->__getLastRequest();
            $ret['response'] = $client->__getLastResponse();
            $ret['status'] = 'sent';
        } catch (\SoapFault $e) {
            if ( isset($client) ) {
                $ret['request'] = $client->__getLastRequest();
                $lastResponse = $client->__getLastResponse();
            }
            if ( isset($lastResponse) && strpos($lastResponse, 'No se detecta certificado electr')!==false ) {
                $ret['response'] = 'No se detecta certificado electrónico';
            } else {
                $ret['response'] = $e->getMessage();
            }
            $ret['status'] = 'fail';
        }
        return $ret;
    } 
/*
[response] => looks like we got no XML document
[response] => SOAP-ERROR: Encoding: object has no 'Cabecera' property
[response] => No se detecta certificado electrónico
[response] => Codigo[103].NIF no identificado: 99999972C/EIDAS CERTIFICADO PRUEBAS
*/
}
