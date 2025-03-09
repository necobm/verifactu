<?php
namespace jdg\Verifactu;

class VeriFactuHashGenerator
{
    /**
     * The required fields for the invoice.
     */
    private static $requiredFields = [
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
     * Generates the hash ("huella") for an invoice record.
     *
     * @param array $data Invoice record data in the correct order.
     * @return string Generated hash in hexadecimal format (64 uppercase characters).
     * @throws \InvalidArgumentException if the data is invalid.
     */
    public static function generateHash(array $data): string
    {
        // Validate the input data
        self::validateData($data);

        // Build the input string in the required format
        $inputString = self::buildInputString($data);

        // Apply the SHA-256 algorithm
        return strtoupper(hash('sha256', $inputString, false));
    }

    /**
     * Validates that the provided data contains only the required fields.
     *
     * @param array $data Data to validate.
     * @throws \InvalidArgumentException if the data contains extra or missing fields.
     */
    private static function validateData(array $data): void
    {
        // Ensure all required fields are present
        $missingFields = array_diff(self::$requiredFields, array_keys($data));
        if (!empty($missingFields)) {
            throw new \InvalidArgumentException('Missing required fields: ' . implode(', ', $missingFields));
        }

        // Ensure no extra fields are present
        $extraFields = array_diff(array_keys($data), self::$requiredFields);
        if (!empty($extraFields)) {
            throw new \InvalidArgumentException('Unexpected fields: ' . implode(', ', $extraFields));
        }
    }

    /**
     * Builds the input string by trimming spaces and formatting numeric values correctly.
     *
     * @param array $data Data to be concatenated.
     * @return string Formatted string according to specifications.
     */
    private static function buildInputString(array $data): string
    {
        $inputParts = [];
        foreach ($data as $key => $value) {
            // Trim spaces at the beginning and end
            $value = trim($value);

            // Properly format numeric values
            if (is_numeric($value)) {
                $value = rtrim(number_format((float)$value, 2, '.', ''), '0');
                $value = rtrim($value, '.');
            }

            // Build the key=value format
            $inputParts[] = "{$key}={$value}";
        }

        return implode('&', $inputParts);
    }
}