<?php
use PHPUnit\Framework\TestCase;
use jdg\Verifactu\VeriFactuStringHelper;

class VeriFactuStringHelperTest extends TestCase
{
    public function testSanitizeString()
    {
        $testString = "  This is a test & another < test.  ";
        $expected = 'This is a test &amp; another &lt; test.';
        $this->assertEquals($expected, VeriFactuStringHelper::sanitizeString($testString));

        $testString = "Testing <script> and &";
        $expected = 'Testing &lt;script> and &amp;';
        $this->assertEquals($expected, VeriFactuStringHelper::sanitizeString($testString));
        
        $testString = "   only spaces     ";
        $expected = 'only spaces';
        $this->assertEquals($expected, VeriFactuStringHelper::sanitizeString($testString));
        
        $testString = "&<";
        $expected = '&amp;&lt;';
        $this->assertEquals($expected, VeriFactuStringHelper::sanitizeString($testString));
    }
}
