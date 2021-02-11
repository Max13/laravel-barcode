<?php

namespace Max13\Barcode;

use Max13\Barcode\Exceptions\InvalidLengthException;

class Ean8 implements BarcodeInterface
{
    /**
     * Returns the checksum digit for a barcode
     *
     * @param  string  $barcode 7 digits barcode
     * @return string           The checksum digit
     *
     * @throws \Max13\Barcode\Exceptions\InvalidLengthException  If the barcode given is
     *                                                           not 7 characters long
     */
    public function checksum($barcode)
    {
        if (!is_string($barcode)) {
            throw new InvalidLengthException('The barcode must be of type string, '.gettype($barcode).' given.');
        }

        if (($len = strlen($barcode)) !== 7) {
            throw new InvalidLengthException("The barcode must be 7 characters long, $len characters barcode given ($barcode).");
        }

        $checksum = 0;
        for ($i=0; $i<7; ++$i) {
            $checksum += intval($barcode[$i]) * ($i % 2 ? 1 : 3);
        }

        return strval(10 - $checksum % 10);
    }

    /**
     * Encode the given barcode to be used with its font
     *
     * @param  string $barcode 8 digits barcode
     * @return string
     */
    public function encode($barcode, $validate = true)
    {
        if ($validate && !$this->isValid($barcode)) {
            throw new InvalidLengthException("The barcode given must be EAN8 valid, $barcode given.");
        }

        // Font encoding table
        $tables = [
            // STARTS
            [':A', ':B', ':C', ':D', ':E', ':F', ':G', ':H', ':I', ':J'],
            // TABLE A
            ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
            // TABLE C
            ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'],
            // TABLE C prepended with the middle delimiter
            ['*a', '*b', '*c', '*d', '*e', '*f', '*g', '*h', '*i', '*j'],
            // TABLE C appended with the end delimiter
            ['a+', 'b+', 'c+', 'd+', 'e+', 'f+', 'g+', 'h+', 'i+', 'j+'],
        ];

        // Tables order to use depending on first digit
        $order = [
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
            [0, 1, 1, 1, 3, 2, 2, 4],
        ];

        $use = intval($barcode[0]);
        $encoded = '';

        for ($i=0; $i<$this->getLength(); ++$i) {
            $encoded .= $tables[$order[$use][$i]][intval($barcode[$i])];
        }

        return $encoded;
    }

    /**
     * Fixes a given barcode by adding or changing the checksum
     *
     * @param  string $barcode 7 or 8 digits barcode
     * @return string          The full EAN8 barcode fixed if necessary
     *
     * @throws \Max13\Barcode\Exceptions\InvalidLengthException  If the barcode given is
     *                                                           not 7 or 8 characters
     *                                                           long
     */
    public function fix($barcode)
    {
        if (!is_string($barcode)) {
            throw new InvalidLengthException('The barcode must be of type string, '.gettype($barcode).' given.');
        }

        if (!in_array($len = strlen($barcode), [7, 8])) {
            throw new InvalidLengthException("The barcode must be 7 or 8 characters long, $len characters barcode given ($barcode).");
        }

        $barcode = substr($barcode, 0, 7);

        return $barcode.$this->checksum($barcode);
    }

    /**
     * Checks if barcode is valid
     *
     * @param  string  $barcode
     * @return boolean
     */
    public function isValid($barcode)
    {
        if (!is_string($barcode)) {
            throw new InvalidLengthException('The barcode must be of type string, '.gettype($barcode).' given.');
        }

        if (($len = strlen($barcode)) !== 8) {
            throw new InvalidLengthException("The barcode must be 8 characters long, $len characters barcode given ($barcode).");
        }

        $checksum = $barcode[7];
        $barcode = substr($barcode, 0, 7);

        return $checksum === $this->checksum($barcode);
    }
}
