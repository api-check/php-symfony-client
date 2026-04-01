<?php

declare(strict_types=1);

namespace ApiCheck\Symfony\Tests;

use ApiCheck\Api\ApiClient;
use ApiCheck\Symfony\ApiCheckBundle;
use ApiCheck\Symfony\DependencyInjection\ApiClientFactory;
use PHPUnit\Framework\TestCase;

final class ApiCheckTest extends TestCase
{
    public function testApiClientFactoryCreatesClient(): void
    {
        $factory = new ApiClientFactory('test-key', 'https://test.com');
        $client = $factory->create();

        $this->assertInstanceOf(ApiClient::class, $client);
    }

    public function testApiClientFactoryThrowsOnEmptyKey(): void
    {
        $factory = new ApiClientFactory('', null);

        $this->expectException(\InvalidArgumentException::class);
        $factory->create();
    }

    public function testBundlePathIsCorrect(): void
    {
        $bundle = new ApiCheckBundle();
        $this->assertDirectoryExists($bundle->getPath());
    }
}
