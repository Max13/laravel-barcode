<?php

namespace Max13\Barcode;

use Illuminate\Support\Facades\Facade;

class Barcode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'barcode';
    }
}
