<?php

namespace BoldlineStudios\RevenueCatApi\Models;

class Subscriber
{
    public function __construct(
        public string $originalAppUserId,
        public string $originalApplicationId,
        public string $firstSeen,
        public string $lastSeen,
        public array $entitlements = [],
        public array $subscriptions = [],
        public array $nonSubscriptionTransactions = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            originalAppUserId: $data['original_app_user_id'] ?? '',
            originalApplicationId: $data['original_application_id'] ?? '',
            firstSeen: $data['first_seen'] ?? '',
            lastSeen: $data['last_seen'] ?? '',
            entitlements: $data['entitlements'] ?? [],
            subscriptions: $data['subscriptions'] ?? [],
            nonSubscriptionTransactions: $data['non_subscription_transactions'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'original_app_user_id' => $this->originalAppUserId,
            'original_application_id' => $this->originalApplicationId,
            'first_seen' => $this->firstSeen,
            'last_seen' => $this->lastSeen,
            'entitlements' => $this->entitlements,
            'subscriptions' => $this->subscriptions,
            'non_subscription_transactions' => $this->nonSubscriptionTransactions,
        ];
    }
}
