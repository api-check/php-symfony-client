<?php

declare(strict_types=1);

use ApiCheck\Api\ApiClient;
use ApiCheck\Symfony\DependencyInjection\ApiClientFactory;
use Symfony\Component\DependencyInjection\Reference;

$container->register('apicheck.api_client_factory', ApiClientFactory::class)
    ->setArguments([null, null]);

$container->register('apicheck.api_client', ApiClient::class)
    ->setFactory([new Reference('apicheck.api_client_factory'), 'create']);

$container->setAlias(ApiClient::class, 'apicheck.api_client');
