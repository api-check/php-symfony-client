<?php

declare(strict_types=1);

namespace ApiCheck\Symfony\DependencyInjection;

use ApiCheck\Api\ApiClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class ApiCheckExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $factoryDef = $container->getDefinition('apicheck.api_client_factory');
        $factoryDef->setArgument(0, $config['api_key']);
        $factoryDef->setArgument(1, $config['referer'] ?? null);
    }

    public function getAlias(): string
    {
        return 'apicheck';
    }
}
