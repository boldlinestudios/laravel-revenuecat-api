<?php

namespace BoldlineStudios\RevenueCatApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \BoldlineStudios\RevenueCatApi\Endpoints\App apps()
 */
class RevenueCatClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'revenuecat';
    }
}
