<?php

namespace Max13\Barcode\Tests;

use Max13\Barcode\Ean13;
use Max13\Barcode\Exceptions\InvalidLengthException;
use Max13\Barcode\Manager;

class Ean13Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('barcode.default', 'ean13');
    }

    public function testManagerIsCorrectlyBoundToContainer()
    {
        $this->assertInstanceOf(Manager::class, $this->app['barcode']);
    }

    public function testDriverIsCorrectlyCreated()
    {
        $this->assertInstanceOf(Ean13::class, $this->app['barcode']->driver());
    }

    public function testChecksumThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        $this->app['barcode']->checksum(123);
    }

    public function testChecksumThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 12 characters long');

        $this->app['barcode']->checksum('123');
    }

    public function testChecksumThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 12 characters long');

        $this->app['barcode']->checksum('1234567891011');
    }

    public function testChecksum()
    {
        $this->assertEquals(
            '5',
            $this->app['barcode']->checksum('505650128715')
        );
        $this->assertEquals(
            '5',
            $this->app['barcode']->checksum('373151581900')
        );
        $this->assertEquals(
            '9',
            $this->app['barcode']->checksum('316058474164')
        );
        $this->assertEquals(
            '6',
            $this->app['barcode']->checksum('445027632564')
        );
        $this->assertEquals(
            '0',
            $this->app['barcode']->checksum('478650601504')
        );
    }

    public function testFixThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        $this->app['barcode']->fix(123);
    }

    public function testFixThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage(
            'The barcode must be either of [12,13] characters long'
        );

        $this->app['barcode']->fix('123');
    }

    public function testFixThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage(
            'The barcode must be either of [12,13] characters long'
        );

        $this->app['barcode']->fix('12345678910123');
    }

    public function testFix()
    {
        // 12 characters
        $this->assertEquals(
            '5056501287155',
            $this->app['barcode']->fix('505650128715')
        );

        // Wrong checksum
        $this->assertEquals(
            '3160584741649',
            $this->app['barcode']->fix('3160584741641')
        );

        // Right checksum
        $this->assertEquals(
            '3731515819005',
            $this->app['barcode']->fix('3731515819005')
        );
    }

    public function testIsValidThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        $this->app['barcode']->isValid(123);
    }

    public function testIsValidThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        $this->app['barcode']->isValid('123');
    }

    public function testIsValidThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        $this->app['barcode']->isValid('12345678910123');
    }

    public function testIsValidReturnsTrueForValidBarcodes()
    {
        $this->assertTrue($this->app['barcode']->isValid('5056501287155'));
        $this->assertTrue($this->app['barcode']->isValid('3731515819005'));
        $this->assertTrue($this->app['barcode']->isValid('3160584741649'));
        $this->assertTrue($this->app['barcode']->isValid('4450276325646'));
        $this->assertTrue($this->app['barcode']->isValid('4786506015040'));
    }

    public function testIsValidReturnsFalseForInvalidBarcodes()
    {
        $this->assertFalse($this->app['barcode']->isValid('5056501287150'));
        $this->assertFalse($this->app['barcode']->isValid('3731515819000'));
        $this->assertFalse($this->app['barcode']->isValid('3160584741640'));
        $this->assertFalse($this->app['barcode']->isValid('4450276325640'));
        $this->assertFalse($this->app['barcode']->isValid('4786506015049'));
    }

    public function testEncodeThrowsExceptionForNotStringArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be of type string');

        $this->app['barcode']->encode(123);
    }

    public function testEncodeThrowsExceptionForTooShortArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        $this->app['barcode']->encode('123');
    }

    public function testEncodeThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 13 characters long');

        $this->app['barcode']->encode('12345678910123');
    }

    public function testEncodeReturnsEncodedBarcodes()
    {
        $this->assertEquals(
            '5APQFAL*cihbff+',
            $this->app['barcode']->encode('5056501287155')
        );
        $this->assertEquals(
            '3HDLPLF*ibjaaf+',
            $this->app['barcode']->encode('3731515819005')
        );
        $this->assertEquals(
            '3BGKPSE*hebgej+',
            $this->app['barcode']->encode('3160584741649')
        );
        $this->assertEquals(
            '4EPACRQ*dcfgeg+',
            $this->app['barcode']->encode('4450276325646')
        );
        $this->assertEquals(
            '4HSGFKQ*abfaea+',
            $this->app['barcode']->encode('4786506015040')
        );
    }
}
