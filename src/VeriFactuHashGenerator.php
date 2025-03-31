<?php
namespace jdgOpenCode\verifactu;

class VeriFactuHashGenerator
{
    /**
     * The required fields for the invoice.
     */
    private static $invoiceRequiredFields = [
        'IDEmisorFactura',
        'NumSerieFactura',
        'FechaExpedicionFactura',
        'TipoFactura',
        'CuotaTotal',
        'ImporteTotal',
        'Huella',
        'FechaHoraHusoGenRegistro'
    ];
    /**
     * The required fields for the invoice cancellation.
     */
    private static $invoiceCancellationRequiredFields = [
        'IDEmisorFacturaAnulada',
        'NumSerieFacturaAnulada',
        'FechaExpedicionFacturaAnulada',
        'Huella',
        'FechaHoraHusoGenRegistro'
    ];
    /**
     * The required fields for the events.
     */
    private static $eventRequiredFields = [
        'NIF_SistemaInformatico',
        'ID',
        'IdSistemaInformatico',
        'Version',
        'NumeroInstalacion',
        'NIF',
        'TipoEvento',
        'HuellaEvento',
        'FechaHoraHusoGenEvento',
    ];

    /**
     * Generates the hash ("huella") for an invoice record.
     *
     * @param array $data Invoice record data in the correct order.
     * @return string Generated hash in hexadecimal format (64 uppercase characters), and the input string.
     * @throws \InvalidArgumentException if the data is invalid.
     */
    public static function generateHashInvoice(array $data): array
    {
        self::validateData(self::$invoiceRequiredFields, $data);
        $inputString = self::getFieldValue('IDEmisorFactura', $data['IDEmisorFactura']);
        $inputString .= self::getFieldValue('NumSerieFactura', $data['NumSerieFactura']);
        $inputString .= self::getFieldValue('FechaExpedicionFactura', $data['FechaExpedicionFactura']);
        $inputString .= self::getFieldValue('TipoFactura', $data['TipoFactura']);
        $inputString .= self::getFieldValue('CuotaTotal', $data['CuotaTotal']);
        $inputString .= self::getFieldValue('ImporteTotal', $data['ImporteTotal']);
        $inputString .= self::getFieldValue('Huella', $data['Huella']);
        $inputString .= self::getFieldValue('FechaHoraHusoGenRegistro', $data['FechaHoraHusoGenRegistro'], false);
        $hash = strtoupper(hash('sha256', $inputString, false));
        return ['hash' => $hash, 'inputString' => $inputString];
    }




    /**
     * Generates the hash ("huella") for an invoice cancellation record.
     *
     * @param array $data Invoice Cancellation record data in the correct order.
     * @return string Generated hash in hexadecimal format (64 uppercase characters).
     * @throws \InvalidArgumentException if the data is invalid.
     */
    public static function generateHashInvoiceCancellation(array $data): array
    {
        self::validateData(self::$invoiceCancellationRequiredFields, $data);
        $inputString = self::getFieldValue('IDEmisorFacturaAnulada', $data['IDEmisorFacturaAnulada']);
        $inputString .= self::getFieldValue('NumSerieFacturaAnulada', $data['NumSerieFacturaAnulada']);
        $inputString .= self::getFieldValue('FechaExpedicionFacturaAnulada', $data['FechaExpedicionFacturaAnulada']);
        $inputString .= self::getFieldValue('Huella', $data['Huella']);
        $inputString .= self::getFieldValue('FechaHoraHusoGenRegistro', $data['FechaHoraHusoGenRegistro'], false);
        $hash = strtoupper(hash('sha256', $inputString, false));
        return ['hash' => $hash, 'inputString' => $inputString];
    }
    
    /**
     * Generates the hash ("huella") for an event record.
     *
     * @param array $data Event record data in the correct order.
     * @return string Generated hash in hexadecimal format (64 uppercase characters).
     * @throws \InvalidArgumentException if the data is invalid.
     */
    public static function generateHashEvent(array $data): array
    {
        self::validateData(self::$eventRequiredFields, $data);
        $inputString = self::getFieldValue('NIF', $data['NIF_SistemaInformatico']);
        $inputString .= self::getFieldValue('ID', $data['ID']);
        $inputString .= self::getFieldValue('IdSistemaInformatico', $data['IdSistemaInformatico']);
        $inputString .= self::getFieldValue('Version', $data['Version']);
        $inputString .= self::getFieldValue('NumeroInstalacion', $data['NumeroInstalacion']);
        $inputString .= self::getFieldValue('NIF', $data['NIF']);
        $inputString .= self::getFieldValue('TipoEvento', $data['TipoEvento']);
        $inputString .= self::getFieldValue('HuellaEvento', $data['HuellaEvento']);
        $inputString .= self::getFieldValue('FechaHoraHusoGenEvento', $data['FechaHoraHusoGenEvento'], false);
        $hash = strtoupper(hash('sha256', $inputString, false));
        return ['hash' => $hash, 'inputString' => $inputString];
    }

    /**
     * Validates that the provided data contains only the required fields.
     *
     * @param array $data Data to validate.
     * @throws \InvalidArgumentException if the data contains extra or missing fields.
     */
    private static function validateData(array $requiredFields, array $data): void
    {
        $missingFields = array_diff($requiredFields, array_keys($data));
        if (!empty($missingFields)) {
            throw new \InvalidArgumentException('Missing required fields: ' . implode(', ', $missingFields));
        }

        $extraFields = array_diff(array_keys($data), $requiredFields);
        if (!empty($extraFields)) {
            throw new \InvalidArgumentException('Unexpected fields: ' . implode(', ', $extraFields));
        }
    }

    /**
     * Get the field and value string, including the optional separator
     *
     * @param string $fieldName The name of the field.
     * @param string $value The value of the field.
     * @param bool $includeSeparator Whether to include the separator.
     * @return string Formatted string according to specifications.
     */
    private static function getFieldValue(string $fieldName, string $value, bool $includeSeparator=true): string
    {
        $value = trim($value);
        return "{$fieldName}={$value}" . ($includeSeparator ? '&' : '');
    }
}
