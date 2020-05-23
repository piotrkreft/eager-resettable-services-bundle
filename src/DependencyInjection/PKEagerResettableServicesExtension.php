<?php

declare(strict_types=1);

namespace PK\EagerResettableServicesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class PKEagerResettableServicesExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('pk_eager_resettable_services.services', $config['services']);
        $container->setParameter('pk_eager_resettable_services.all_services', $config['all_services']);
    }
}
