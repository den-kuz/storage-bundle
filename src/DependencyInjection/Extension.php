<?php

declare(strict_types=1);

namespace D3N\StorageBundle\DependencyInjection;

use D3N\StorageBundle\Storage\Storage;
use D3N\StorageBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class Extension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $this->registerStorages($container, $mergedConfig['storage']);
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
                ->setDefinition($id, new Definition(Storage::class, ['$storageDir' => $path, '$name' => $name]))
                ->setPublic(false)
                ->setAutowired(true)
                ->addTag('d3n.storage');
        }

        $defaultStorage = $config['default'] ?? null;

        if (null !== $defaultStorage && isset($config['storages'][$defaultStorage])) {
            $container->setAlias(StorageInterface::class, sprintf('d3n.storage.%s', $defaultStorage));
        }
    }
}
