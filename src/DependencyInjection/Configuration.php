<?php

declare(strict_types=1);

namespace ApiCheck\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('apicheck');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->info('Your ApiCheck API key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('referer')
                    ->info('Referer URL for API keys with "Allowed Hosts" enabled')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
