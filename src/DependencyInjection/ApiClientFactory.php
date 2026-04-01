<?php

declare(strict_types=1);

namespace ApiCheck\Symfony\DependencyInjection;

use ApiCheck\Api\ApiClient;

final class ApiClientFactory
{
    public function __construct(
        private ?string $apiKey,
        private ?string $referer
    ) {}

    public function create(): ApiClient
    {
        if (empty($this->apiKey)) {
            throw new \InvalidArgumentException('ApiCheck API key cannot be empty');
        }

        $client = new ApiClient();
        $client->setApiKey($this->apiKey);

        if ($this->referer) {
            $client->setReferer($this->referer);
        }

        return $client;
    }
}
