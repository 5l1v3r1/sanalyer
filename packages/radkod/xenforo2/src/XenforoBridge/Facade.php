<?php

namespace Radkod\Xenforo2\XenforoBridge;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'xenforobridge';
    }
}