<?php

declare(strict_types=1);

namespace D3N\StorageBundle\DependencyInjection;

use D3N\StorageBundle\Storage\Storage;
use D3N\StorageBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;
use function sprintf;

final class StorageExtension extends BaseExtension
{
    public function getAlias(): string
    {
        return 'd3n_storage';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);

        if (null === $configuration) {
            return;
        }

        $this->registerStorages($container, $this->processConfiguration($configuration, $configs));
    }

    private function registerStorages(ContainerBuilder $container, array $config): void
    {
        if (!$this->isConfigEnabled($container, $config) || ([] === ($config['storages'] ?? []))) {
            return;
        }

        foreach ($config['storages'] ?? [] as $name => $path) {
            $id = sprintf('d3n.storage.%s', $name);
            $container->registerAliasForArgument($id, StorageInterface::class, sprintf('%sStorage', $name));

            $container
                ->setDefinition($id, new Definition(Storage::class, ['$dir' => $path, '$name' => $name]))
                ->setPublic(false)
                ->setAutowired(true)
                ->addTag('d3n.storage')
            ;
        }

        $defaultStorage = $config['default'] ?? null;

        if (null !== $defaultStorage && isset($config['storages'][$defaultStorage])) {
            $container->setAlias(StorageInterface::class, sprintf('d3n.storage.%s', $defaultStorage));
        }
    }
}
