<?php

namespace Max13\Barcode\Facades;

use Illuminate\Support\Facades\Facade;

class Barcode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'barcode';
    }
}
