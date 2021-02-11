<?php

namespace Max13\Barcode\Tests;

use Max13\Barcode\Ean8;
use Max13\Barcode\Exceptions\InvalidLengthException;
use Max13\Barcode\Manager;

class Ean8Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('barcode.default', 'ean8');
    }

    public function testManagerIsCorrectlyBoundToContainer()
    {
        $this->assertInstanceOf(Manager::class, $this->app['barcode']);
    }

    public function testDriverIsCorrectlyCreated()
    {
        $this->assertInstanceOf(Ean8::class, $this->app['barcode']->driver());
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
        $this->expectExceptionMessage('The barcode must be 7 characters long');

        $this->app['barcode']->checksum('123');
    }

    public function testChecksumThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 7 characters long');

        $this->app['barcode']->checksum('1234567891011');
    }

    public function testChecksum()
    {
        $this->assertEquals('7', $this->app['barcode']->checksum('4719512'));
        $this->assertEquals('3', $this->app['barcode']->checksum('3731515'));
        $this->assertEquals('7', $this->app['barcode']->checksum('3160584'));
        $this->assertEquals('8', $this->app['barcode']->checksum('4450276'));
        $this->assertEquals('8', $this->app['barcode']->checksum('4786506'));
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
            'The barcode must be 7 or 8 characters long'
        );

        $this->app['barcode']->fix('123');
    }

    public function testFixThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage(
            'The barcode must be 7 or 8 characters long'
        );

        $this->app['barcode']->fix('12345678910123');
    }

    public function testFix()
    {
        // 7 characters
        $this->assertEquals('47195127', $this->app['barcode']->fix('4719512'));

        // Wrong checksum
        $this->assertEquals('37315153', $this->app['barcode']->fix('37315157'));

        // Right checksum
        $this->assertEquals('31605847', $this->app['barcode']->fix('31605847'));
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
        $this->expectExceptionMessage('The barcode must be 8 characters long');

        $this->app['barcode']->isValid('123');
    }

    public function testIsValidThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 8 characters long');

        $this->app['barcode']->isValid('12345678910123');
    }

    public function testIsValidReturnsTrueForValidBarcodes()
    {
        $this->assertTrue($this->app['barcode']->isValid('47195127'));
        $this->assertTrue($this->app['barcode']->isValid('37315153'));
        $this->assertTrue($this->app['barcode']->isValid('31605847'));
        $this->assertTrue($this->app['barcode']->isValid('44502768'));
        $this->assertTrue($this->app['barcode']->isValid('47865068'));
    }

    public function testIsValidReturnsFalseForInvalidBarcodes()
    {
        $this->assertFalse($this->app['barcode']->isValid('47195123'));
        $this->assertFalse($this->app['barcode']->isValid('37315152'));
        $this->assertFalse($this->app['barcode']->isValid('31605849'));
        $this->assertFalse($this->app['barcode']->isValid('44502767'));
        $this->assertFalse($this->app['barcode']->isValid('47865064'));
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
        $this->expectExceptionMessage('The barcode must be 8 characters long');

        $this->app['barcode']->encode('123');
    }

    public function testEncodeThrowsExceptionForTooLongArgument()
    {
        $this->expectException(InvalidLengthException::class);
        $this->expectExceptionMessage('The barcode must be 8 characters long');

        $this->app['barcode']->encode('123456789');
    }

    public function testEncodeReturnsEncodedBarcodes()
    {
        $this->assertEquals(
            ':EHBJ*fbch+',
            $this->app['barcode']->encode('47195127')
        );
        $this->assertEquals(
            ':DHDB*fbfd+',
            $this->app['barcode']->encode('37315153')
        );
        $this->assertEquals(
            ':DBGA*fieh+',
            $this->app['barcode']->encode('31605847')
        );
        $this->assertEquals(
            ':EEFA*chgi+',
            $this->app['barcode']->encode('44502768')
        );
        $this->assertEquals(
            ':EHIG*fagi+',
            $this->app['barcode']->encode('47865068')
        );
    }
}
