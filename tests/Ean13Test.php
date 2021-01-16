<?php

namespace Max13\Barcode\Tests;

use Max13\Barcode\Barcode;
use Max13\Barcode\Ean13;
use Max13\Barcode\Exceptions\InvalidLengthException;

class Ean13Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('barcode.default', 'ean13');
    }

    public function testClassIsCorrectlyBoundToContainer()
    {
        $this->assertInstanceOf(Ean13::class, $this->app['barcode']);
    }

    public function testChecksumThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        Barcode::checksum(123);
    }

    public function testChecksumThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 12 characters long');

        Barcode::checksum('123');
    }

    public function testChecksumThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 12 characters long');

        Barcode::checksum('1234567891011');
    }

    public function testChecksum()
    {
        $this->assertEquals('5', Barcode::checksum('505650128715'));
        $this->assertEquals('5', Barcode::checksum('373151581900'));
        $this->assertEquals('9', Barcode::checksum('316058474164'));
        $this->assertEquals('6', Barcode::checksum('445027632564'));
        $this->assertEquals('9', Barcode::checksum('478650601501'));
    }

    public function testFixThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        Barcode::fix(123);
    }

    public function testFixThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 12 or 13 characters long');

        Barcode::fix('123');
    }

    public function testFixThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 12 or 13 characters long');

        Barcode::fix('12345678910123');
    }

    public function testFix()
    {
        // 12 characters
        $this->assertEquals('5056501287155', Barcode::fix('505650128715'));

        // Wrong checksum
        $this->assertEquals('3160584741649', Barcode::fix('3160584741641'));

        // Right checksum
        $this->assertEquals('3731515819005', Barcode::fix('3731515819005'));
    }

    public function testIsValidThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        Barcode::isValid(123);
    }

    public function testIsValidThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        Barcode::isValid('123');
    }

    public function testIsValidThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        Barcode::isValid('12345678910123');
    }

    public function testIsValidReturnsTrueForValidBarcodes()
    {
        $this->assertTrue(Barcode::isValid('5056501287155'));
        $this->assertTrue(Barcode::isValid('3731515819005'));
        $this->assertTrue(Barcode::isValid('3160584741649'));
        $this->assertTrue(Barcode::isValid('4450276325646'));
        $this->assertTrue(Barcode::isValid('4786506015019'));
    }

    public function testIsValidReturnsFalseForInvalidBarcodes()
    {
        $this->assertFalse(Barcode::isValid('5056501287150'));
        $this->assertFalse(Barcode::isValid('3731515819000'));
        $this->assertFalse(Barcode::isValid('3160584741640'));
        $this->assertFalse(Barcode::isValid('4450276325640'));
        $this->assertFalse(Barcode::isValid('4786506015010'));
    }

    public function testEncodeThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        Barcode::encode(123);
    }

    public function testEncodeThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        Barcode::encode('123');
    }

    public function testEncodeThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        Barcode::encode('12345678910123');
    }

    public function testEncodeReturnsEncodedBarcodes()
    {
        $this->assertEquals('5APQFAL*cihbff+', Barcode::encode('5056501287155'));
        $this->assertEquals('3HDLPLF*ibjaaf+', Barcode::encode('3731515819005'));
        $this->assertEquals('3BGKPSE*hebgej+', Barcode::encode('3160584741649'));
        $this->assertEquals('4EPACRQ*dcfgeg+', Barcode::encode('4450276325646'));
        $this->assertEquals('4HSGFKQ*abfabj+', Barcode::encode('4786506015019'));
    }
}
