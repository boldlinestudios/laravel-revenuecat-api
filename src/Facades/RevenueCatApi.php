<?php

namespace BoldlineStudios\RevenueCatApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response getSubscriber(string $appUserId)
 * @method static \Illuminate\Http\Client\Response getSubscriberEntitlements(string $appUserId)
 * @method static \Illuminate\Http\Client\Response grantPromotionalEntitlement(string $appUserId, array<string, mixed> $data)
 * @method static \Illuminate\Http\Client\Response revokePromotionalEntitlement(string $appUserId, string $entitlementId)
 * @method static \Illuminate\Http\Client\Response getProducts()
 * @method static \Illuminate\Http\Client\Response getOfferings()
 * @method static \Illuminate\Http\Client\Response request(string $method, string $endpoint, array<string, mixed> $data = [])
 *
 * @see \BoldlineStudios\RevenueCatApi\Services\RevenueCatApiService
 */
class RevenueCatApi extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'revenuecat-api';
    }
}
