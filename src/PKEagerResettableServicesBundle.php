<?php

declare(strict_types=1);

namespace PK\EagerResettableServicesBundle;

use PK\EagerResettableServicesBundle\DependencyInjection\Compiler\EagerResettableServicesPass;
use PK\EagerResettableServicesBundle\DependencyInjection\Compiler\ValidateServicesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PKEagerResettableServicesBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new ValidateServicesPass())
            ->addCompilerPass(new EagerResettableServicesPass(), PassConfig::TYPE_REMOVE)
        ;
    }
}
