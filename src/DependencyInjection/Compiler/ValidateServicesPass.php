<?php

declare(strict_types=1);

namespace PK\EagerResettableServicesBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ValidateServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $notExisting = [];
        foreach ($container->getParameter('pk_eager_resettable_services.services') as $serviceId) {
            if ($container->hasDefinition($serviceId) || $container->hasAlias($serviceId)) {
                continue;
            }
            $notExisting[] = $serviceId;
        }
        if (!$notExisting) {
            return;
        }

        throw new InvalidConfigurationException(sprintf(
            'Missing resettable services for eager initialization (%s).',
            implode(', ', $notExisting)
        ));
    }
}
