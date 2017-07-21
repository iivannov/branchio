<?php

namespace Iivannov\Branchio\Integration\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Iivannov\Branchio\Client
 */
class Branchio extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Iivannov\Branchio\Client::class;
    }
}