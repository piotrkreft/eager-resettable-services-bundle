<?php

declare(strict_types=1);

namespace PK\EagerResettableServicesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Argument\ArgumentInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EagerResettableServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $allServices = $container->getParameter('pk_eager_resettable_services.all_services');
        $services = $container->getParameter('pk_eager_resettable_services.services');

        if (!$container->has('services_resetter') || !$services && !$allServices) {
            return;
        }

        /** @var ArgumentInterface $resettableServices */
        $resettableServices = $container->getDefinition('services_resetter')->getArgument(0);
        $overrideResettableServices = [];

        foreach ($resettableServices->getValues() as $serviceId => $reference) {
            if (($allServices || in_array($serviceId, $services)) && $container->hasDefinition($serviceId)) {
                $overrideResettableServices[$serviceId] = new Reference($serviceId);
                continue;
            }
            $overrideResettableServices[$serviceId] = $reference;
        }

        $resettableServices->setValues($overrideResettableServices);
    }
}
