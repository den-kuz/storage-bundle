<?php

declare(strict_types=1);

namespace D3N\StorageBundle\DependencyInjection\Compiler;

use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function sprintf;

final class StoragePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('d3n.storage') as $serviceId => $tags) {
            $storageDef = $container->getDefinition($serviceId);
            $dir = $container->resolveEnvPlaceholders($storageDef->getArgument('$storageDir'), true);

            if (!is_dir($dir)) {
                if (false === @mkdir($dir, 0o777, true) && !is_dir($dir)) {
                    throw new RuntimeException(sprintf('Failed to create "%s":', $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new RuntimeException(sprintf('Unable to write in the directory (%s).', $dir));
            }
        }
    }
}
