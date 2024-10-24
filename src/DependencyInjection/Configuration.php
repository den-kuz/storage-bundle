<?php

declare(strict_types=1);

namespace D3N\StorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('storage_bundle');
        $this->addStorageSection($treeBuilder->getRootNode());

        return $treeBuilder;
    }

    private function addStorageSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('storage')
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
                ->end()
            ->end();
    }
}
