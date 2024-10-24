<?php

declare(strict_types=1);


use D3N\StorageBundle\DependencyInjection\CompilerPass\StoragePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class StorageBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new StoragePass());
    }
}
