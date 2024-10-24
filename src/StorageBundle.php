<?php

declare(strict_types=1);

namespace D3N\StorageBundle;

use D3N\StorageBundle\DependencyInjection\Compiler\StoragePass;
use D3N\StorageBundle\DependencyInjection\StorageExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class StorageBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new StorageExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new StoragePass());
    }
}
