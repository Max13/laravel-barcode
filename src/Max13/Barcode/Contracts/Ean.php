<?php

namespace Max13\Barcode\Contracts;

use Max13\Barcode\Exceptions\InvalidLengthException;

abstract class Ean implements Barcode
{
    /**
     * Get length of a full implementation barcode
     *
     * @return int
     */
    abstract public function getLength();

    /**
     * Check length and type of the given barcode
     *
     * @param  string $barcode
     * @param  array  $lengths  Barcode lengths to accept (defaults to [])
     *                          An empty array will check for $this->length
     *
     * @return bool
     *
     * @throws \Max13\Barcode\Exceptions\InvalidLengthException
     *         If the given barcode is not the right length
     */
    protected function checkLengthAndType($barcode, array $lengths = [])
    {
        if (!is_string($barcode)) {
            throw new InvalidLengthException('The barcode must be of type string, '.gettype($barcode).' given.');
        }

        if (count($lengths) <= 1) {
            $length = $lengths[0] ?? $this->getLength();
            if (($len = strlen($barcode)) !== $length) {
                throw new InvalidLengthException("The barcode must be $length characters long, $len characters barcode given ($barcode).");
            }
        } else {
            if (!in_array($len = strlen($barcode), $lengths)) {
                $lengthsTxt = implode(',', $lengths);
                throw new InvalidLengthException("The barcode must be either of [$lengthsTxt] characters long, $len characters barcode given ($barcode).");
            }
        }

        return true;
    }

    /**
     * Returns the checksum digit for a barcode
     *
     * @param  string  $barcode
     * @return string           The checksum digit
     *
     * @throws \Max13\Barcode\Exceptions\InvalidLengthException
     *         If the barcode given is not the right length
     *
     * @note   Implementation must call $this->checkLengthAndType($barcode)
     *         to check for length and type of the given barcode
     */
    abstract public function checksum($barcode);

    /**
     * Returns the encoding tables to encode the barcode for its font
     *
     * @return array
     *
     * @note Current font used:
     *       https://grandzebu.net/informatique/codbar-en/ean13.htm
     */
    abstract protected function encodingTables();

    /**
     * Returns the order of tables to use to encode the barcode for its font
     *
     * @return array
     *
     * @note Current font used:
     *       https://grandzebu.net/informatique/codbar-en/ean13.htm
     */
    abstract protected function encodingTablesOrder();

    /**
     * Encode the given barcode to be used with its font
     *
     * @param  string $barcode
     *
     * @return string
     */
    public function encode($barcode, $validate = true)
    {
        if ($validate && !$this->isValid($barcode)) {
            throw new InvalidLengthException("The barcode given must be EAN13 valid, $barcode given.");
        }

        $use = intval($barcode[0]);
        $encoded = '';

        for ($i=0; $i<$this->getLength(); ++$i) {
            $x = $this->encodingTablesOrder()[$use][$i];
            $y = intval($barcode[$i]);
            $encoded .= $this->encodingTables()[$x][$y];
        }

        return $encoded;
    }

    /**
     * Fixes a given barcode by adding or changing the checksum
     *
     * @param  string $barcode
     * @return string          The full barcode fixed if necessary
     *
     * @throws \Max13\Barcode\Exceptions\InvalidLengthException
     *         If the barcode given is not the right length
     */
    public function fix($barcode)
    {
        $this->checkLengthAndType($barcode, [$this->getLength() - 1, $this->getLength()]);

        $barcode = substr($barcode, 0, $this->getLength() - 1);

        return $barcode.$this->checksum($barcode);
    }

    /**
     * Checks if barcode is valid
     *
     * @param  string  $barcode
     * @return bool
     *
     * @throws \Max13\Barcode\Exceptions\InvalidLengthException
     *         If the barcode given is not the right length
     */
    public function isValid($barcode)
    {
        $this->checkLengthAndType($barcode, [$this->getLength()]);

        $checksum = $barcode[$this->getLength() - 1];
        $barcode = substr($barcode, 0, $this->getLength() - 1);

        return $checksum === $this->checksum($barcode);
    }
}
