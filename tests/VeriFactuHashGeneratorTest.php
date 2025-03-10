<?php
use PHPUnit\Framework\TestCase;
use jdg\Verifactu\VeriFactuHashGenerator;

class VeriFactuHashGeneratorTest extends TestCase
{
    /**
     * Test generating the hash with valid invoice data (First Invoice).
     */
    public function testGenerateHashInvoiceFirstInvoiceValidData()
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
        $generatedHash = VeriFactuHashGenerator::generateHashInvoice($invoiceData);

        // Assert that the generated hash matches the expected hash
        $this->assertEquals($expectedHash, $generatedHash);
        $this->assertEquals($expectedHash, '3C464DAF61ACB827C65FDA19F352A4E3BDC2C640E9E9FC4CC058073F38F12F60');
    }
    /**
     * Test generating the hash with valid invoice data (Second Invoice).
     */
    public function testGenerateHashInvoiceSecondInvoiceValidData()
    {
        // Prepare valid invoice data
        $invoiceData = [
            'IDEmisorFactura' => '89890001K',
            'NumSerieFactura' => '12345679/G34',
            'FechaExpedicionFactura' => '01-01-2024',
            'TipoFactura' => 'F1',
            'CuotaTotal' => '12.35',
            'ImporteTotal' => '123.45',
            'Huella' => '3C464DAF61ACB827C65FDA19F352A4E3BDC2C640E9E9FC4CC058073F38F12F60',
            'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:35+01:00',
        ];

        // Expected hash result (based on the given data)
        $expectedHash = strtoupper(hash('sha256', 'IDEmisorFactura=89890001K&NumSerieFactura=12345679/G34&FechaExpedicionFactura=01-01-2024&TipoFactura=F1&CuotaTotal=12.35&ImporteTotal=123.45&Huella=3C464DAF61ACB827C65FDA19F352A4E3BDC2C640E9E9FC4CC058073F38F12F60&FechaHoraHusoGenRegistro=2024-01-01T19:20:35+01:00', false));

        // Generate the hash using the VeriFactuHashGenerator
        $generatedHash = VeriFactuHashGenerator::generateHashInvoice($invoiceData);

        // Assert that the generated hash matches the expected hash
        $this->assertEquals($expectedHash, $generatedHash);
        $this->assertEquals($expectedHash, 'F7B94CFD8924EDFF273501B01EE5153E4CE8F259766F88CF6ACB8935802A2B97');
    }
    /**
     * Test generating the hash with valid invoice data (Cancelled Invoice).
     */
    public function testGenerateHashInvoiceCancellationValidData()
    {
        // Prepare valid invoice data
        $invoiceData = [
            'IDEmisorFacturaAnulada' => '89890001K',
            'NumSerieFacturaAnulada' => '12345679/G34',
            'FechaExpedicionFacturaAnulada' => '01-01-2024',
            'Huella' => 'F7B94CFD8924EDFF273501B01EE5153E4CE8F259766F88CF6ACB8935802A2B97',
            'FechaHoraHusoGenRegistro' => '2024-01-01T19:20:40+01:00'
        ];

        // Expected hash result (based on the given data)
        $expectedHash = strtoupper(hash('sha256', 'IDEmisorFacturaAnulada=89890001K&NumSerieFacturaAnulada=12345679/G34&FechaExpedicionFacturaAnulada=01-01-2024&Huella=F7B94CFD8924EDFF273501B01EE5153E4CE8F259766F88CF6ACB8935802A2B97&FechaHoraHusoGenRegistro=2024-01-01T19:20:40+01:00', false));

        // Generate the hash using the VeriFactuHashGenerator
        $generatedHash = VeriFactuHashGenerator::generateHashInvoiceCancellation($invoiceData);

        // Assert that the generated hash matches the expected hash
        $this->assertEquals($expectedHash, $generatedHash);
        $this->assertEquals($expectedHash, '177547C0D57AC74748561D054A9CEC14B4C4EA23D1BEFD6F2E69E3A388F90C68');
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
        VeriFactuHashGenerator::generateHashInvoice($invoiceData);
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
        VeriFactuHashGenerator::generateHashInvoice($invoiceData);
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
        VeriFactuHashGenerator::generateHashInvoice($invoiceData);
    }
}
