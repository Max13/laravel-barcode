<?php

namespace Max13\Barcode;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Create en Ean13 driver
     *
     * @return \Max13\Barcode\Ean13
     */
    protected function createEan13Driver()
    {
        return new Ean13;
    }

    /** {@inheritDoc} */
    public function getDefaultDriver()
    {
        return $this->config->get('barcode.driver', 'ean13');
    }
}
