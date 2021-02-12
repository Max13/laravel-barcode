<?php

namespace Max13\Barcode;

use Max13\Barcode\Contracts\Ean;

class Ean8 extends Ean
{
    /** {@inheritDoc} */
    public function getLength()
    {
        return 8;
    }

    /** {@inheritDoc} */
    public function checksum($barcode)
    {
        $this->checkLengthAndType($barcode, [$this->getLength() - 1]);

        $checksum = 0;
        for ($i=0; $i<$this->getLength() - 1; ++$i) {
            $checksum += intval($barcode[$i]) * ($i % 2 ? 1 : 3);
        }

        return strval(10 - $checksum % 10);
    }

    /** {@inheritDoc} */
    protected function encodingTables()
    {
        return [
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
    }

    /** {@inheritDoc} */
    protected function encodingTablesOrder()
    {
        return [
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
    }
}
