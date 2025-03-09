<?php
use PHPUnit\Framework\TestCase;
use jdg\Verifactu\VeriFactuHashGenerator;

class VeriFactuHashGeneratorTest extends TestCase
{
    /**
     * Test generating the hash with valid invoice data.
     */
    public function testGenerateHashValidData()
    {
        // Prepare valid invoice data
        $invoiceData = [
            'IDEmisorFactura' => '89890001K',
            'NumSerieFactura' => '12345678/G33',
            'FechaExpedicionFactura' => '01-01-2024',
            'TipoFactura' => 'F1',
            'CuotaTotal' => '12.35',
            'ImporteTotal' => '123.45',
            'Huella' => '',  // Primer registro, sin huella anterior
            'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:30+01:00',
        ];

        // Expected hash result (based on the given data)
        $expectedHash = strtoupper(hash('sha256', 'IDEmisorFactura=89890001K&NumSerieFactura=12345678/G33&FechaExpedicionFactura=01-01-2024&TipoFactura=F1&CuotaTotal=12.35&ImporteTotal=123.45&Huella=&FechaHoraHusoGenRegistro=2024-01-01T19:20:30+01:00', false));

        // Generate the hash using the VeriFactuHashGenerator
        $generatedHash = VeriFactuHashGenerator::generateHash($invoiceData);

        // Assert that the generated hash matches the expected hash
        $this->assertEquals($expectedHash, $generatedHash);
    }

    /**
     * Test generating the hash with missing fields.
     */
    public function testGenerateHashMissingFields()
    {
        // Prepare invoice data missing the 'IDEmisorFactura'
        $invoiceData = [
            'NumSerieFactura' => '12345678/G33',
            'FechaExpedicionFactura' => '01-01-2024',
            'TipoFactura' => 'F1',
            'CuotaTotal' => '12.35',
            'ImporteTotal' => '123.45',
            'Huella' => '',  // Primer registro, sin huella anterior
            'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:30+01:00',
        ];

        // Expect an exception to be thrown due to the missing 'IDEmisorFactura'
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: IDEmisorFactura');

        // Generate the hash (this should fail)
        VeriFactuHashGenerator::generateHash($invoiceData);
    }

    /**
     * Test generating the hash with extra fields.
     */
    public function testGenerateHashExtraFields()
    {
        // Prepare invoice data with an extra field 'ExtraField'
        $invoiceData = [
            'IDEmisorFactura' => '89890001K',
            'NumSerieFactura' => '12345678/G33',
            'FechaExpedicionFactura' => '01-01-2024',
            'TipoFactura' => 'F1',
            'CuotaTotal' => '12.35',
            'ImporteTotal' => '123.45',
            'Huella' => '',  // Primer registro, sin huella anterior
            'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:30+01:00',
            'ExtraField' => 'Some value', // Extra field that shouldn't be there
        ];

        // Expect an exception to be thrown due to the extra 'ExtraField'
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected fields: ExtraField');

        // Generate the hash (this should fail)
        VeriFactuHashGenerator::generateHash($invoiceData);
    }

    /**
     * Test generating the hash with empty data.
     */
    public function testGenerateHashEmptyData()
    {
        // Prepare empty invoice data
        $invoiceData = [];

        // Expect an exception to be thrown due to missing all fields
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: IDEmisorFactura, NumSerieFactura, FechaExpedicionFactura, TipoFactura, CuotaTotal, ImporteTotal, Huella, FechaHoraHusoGenRegistro');

        // Generate the hash (this should fail)
        VeriFactuHashGenerator::generateHash($invoiceData);
    }
}
