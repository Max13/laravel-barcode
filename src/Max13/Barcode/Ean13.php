<?php

namespace Max13\Barcode;

use Max13\Barcode\Contracts\Ean;

class Ean13 extends Ean
{
    /** {@inheritDoc} */
    public function getLength()
    {
        return 13;
    }

    /** {@inheritDoc} */
    public function checksum($barcode)
    {
        $this->checkLengthAndType($barcode, [$this->getLength() - 1]);

        $checksum = 0;
        for ($i=0; $i<12; ++$i) {
            $checksum += intval($barcode[$i]) * ($i % 2 ? 3 : 1);
        }

        return strval(10 - $checksum % 10);
    }

    /** {@inheritDoc} */
    protected function encodingTables()
    {
        return [
            // STARTS
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            // TABLE A
            ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
            // TABLE B
            ['K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'],
            // TABLE C
            ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'],
            // TABLE C prepended with the middle delimiter
            ['*a', '*b', '*c', '*d', '*e', '*f', '*g', '*h', '*i', '*j'],
            // TABLE C appended with the end delimiter
            ['a+', 'b+', 'c+', 'd+', 'e+', 'f+', 'g+', 'h+', 'i+', 'j+'],
        ];
    }

    /** {@inheritDoc} */
    protected function encodingTablesOrder()
    {
        return [
            [0, 1, 1, 1, 1, 1, 1, 4, 3, 3, 3, 3, 5],
            [0, 1, 1, 2, 1, 2, 2, 4, 3, 3, 3, 3, 5],
            [0, 1, 1, 2, 2, 1, 2, 4, 3, 3, 3, 3, 5],
            [0, 1, 1, 2, 2, 2, 1, 4, 3, 3, 3, 3, 5],
            [0, 1, 2, 1, 1, 2, 2, 4, 3, 3, 3, 3, 5],
            [0, 1, 2, 2, 1, 1, 2, 4, 3, 3, 3, 3, 5],
            [0, 1, 2, 2, 2, 1, 1, 4, 3, 3, 3, 3, 5],
            [0, 1, 2, 1, 2, 1, 2, 4, 3, 3, 3, 3, 5],
            [0, 1, 2, 1, 2, 2, 1, 4, 3, 3, 3, 3, 5],
            [0, 1, 2, 2, 1, 2, 1, 4, 3, 3, 3, 3, 5],
        ];
    }
}
