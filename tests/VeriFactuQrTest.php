<?php
use PHPUnit\Framework\TestCase;
use jdgOpenCode\verifactu\VeriFactuQr;

class VeriFactuQrTest extends TestCase
{
    public function testQrUrlPreProduccion()
    {
        $expected = 'https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR?nif=89890001K&numserie=12345678%26G33&fecha=01-09-2024&importe=241.4';
        $invoiceDate = new DateTime('2024-09-01');
        $actual = VeriFactuQr::Url(false, '89890001K', '12345678&G33', $invoiceDate, 241.40);
        $this->assertEquals($expected, $actual);
    }
}
