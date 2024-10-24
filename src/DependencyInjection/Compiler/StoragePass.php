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
            $definition = $container->getDefinition($serviceId);
            $directory = $container->resolveEnvPlaceholders($definition->getArgument('$dir'), true);

            if (!is_dir($directory)) {
                if (false === @mkdir($directory, 0o777, true) && !is_dir($directory)) {
                    throw new RuntimeException(sprintf('Failed to create "%s":', $directory));
                }
            } elseif (!is_writable($directory)) {
                throw new RuntimeException(sprintf('Unable to write in the directory (%s).', $directory));
            }
        }
    }
}
