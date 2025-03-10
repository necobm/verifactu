<?php
use PHPUnit\Framework\TestCase;
use jdg\Verifactu\VeriFactuDateTimeHelper;

class VeriFactuDateTimeHelperTest extends TestCase
{
    public function testFormatDateWithDateTimeObject()
    {
        $dateTime = new DateTime('2024-03-15 10:30:00+00:00');
        $expected = '15-03-2024';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDate($dateTime));

        $dateTime = new DateTime('2024-03-15 10:30:00+01:00');
        $expected = '15-03-2024';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDate($dateTime));
    }

    public function testFormatDateWithString()
    {
        $dateTimeString = '2024-03-15 10:30:00+00:00';
        $expected = '15-03-2024';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDate($dateTimeString));

        $dateTimeString = '2024-03-15 10:30:00';
        $expected = '15-03-2024';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDate($dateTimeString));

        $dateTimeString = '2024-03-15T10:30:00Z';
        $expected = '15-03-2024';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDate($dateTimeString));

        $dateTimeString = '2024-03-15';
        $expected = '15-03-2024';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDate($dateTimeString));
    }
    public function testFormatDateWithInvalidString()
    {
        $this->expectException(Exception::class);
        VeriFactuDateTimeHelper::formatDate('invalid date');
    }
    public function testFormatDateWithInvalidInputType()
    {
        $this->expectException(Exception::class);
        VeriFactuDateTimeHelper::formatDate(123);
    }

    // ----------------------------

    public function testFormatDateTimeISO8601WithDateTimeObject()
    {
        $dateTime = new DateTime('2024-03-15 10:30:00+00:00');
        $expected = '2024-03-15T10:30:00+00:00';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDateTimeISO8601($dateTime));

        $dateTime = new DateTime('2024-03-15 10:30:00+01:00');
        $expected = '2024-03-15T10:30:00+01:00';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDateTimeISO8601($dateTime));
    }

    public function testFormatDateTimeISO8601WithString()
    {
        $dateTimeString = '2024-03-15 10:30:00+00:00';
        $expected = '2024-03-15T10:30:00+00:00';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDateTimeISO8601($dateTimeString));

        $dateTimeString = '2024-03-15 10:30:00';
        $expected = (new DateTime($dateTimeString))->format('c');
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDateTimeISO8601($dateTimeString));

        $dateTimeString = '2024-03-15T10:30:00Z';
        $expected = '2024-03-15T10:30:00+00:00';
        $this->assertEquals($expected, VeriFactuDateTimeHelper::formatDateTimeISO8601($dateTimeString));
    }

    public function testFormatDateTimeISO8601WithInvalidString()
    {
        $this->expectException(Exception::class);
        VeriFactuDateTimeHelper::formatDateTimeISO8601('invalid date');
    }

    public function testFormatDateTimeISO8601WithInvalidInputType()
    {
        $this->expectException(Exception::class);
        VeriFactuDateTimeHelper::formatDateTimeISO8601(123);
    }
}
