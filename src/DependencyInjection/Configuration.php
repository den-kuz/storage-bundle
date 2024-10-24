<?php

declare(strict_types=1);

namespace D3N\StorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('d3n_storage');
        $treeBuilder->getRootNode()
            ->canBeEnabled()
            ->children()
                ->scalarNode('default')->defaultValue('main')->end()
                ->arrayNode('storages')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()
                        ->isRequired()
                        ->cannotBeEmpty()
                     ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
