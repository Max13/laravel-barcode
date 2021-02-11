<?php

namespace Max13\Barcode;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Create en Ean8 driver
     *
     * @return \Max13\Barcode\Ean8
     */
    protected function createEan8Driver()
    {
        return new Ean8;
    }

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
        $default = $this->config->get('barcode.default', 'ean13');

        return $this->config->get("barcode.types.$default.driver");
    }
}
