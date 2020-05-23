<?php

declare(strict_types=1);

namespace PK\Tests\EagerResettableServicesBundle\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use PK\EagerResettableServicesBundle\DependencyInjection\Compiler\ValidateServicesPass;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ValidateServicesPassTest extends TestCase
{
    /**
     * @var ValidateServicesPass
     */
    private $compilerPass;

    protected function setUp(): void
    {
        $this->compilerPass = new ValidateServicesPass();
    }

    public function testShouldProcess(): void
    {
        // given
        $container = new ContainerBuilder();
        $container->setParameter('pk_eager_resettable_services.services', ['service_id']);
        $container->setDefinition('service_id', new Definition());
        $this->expectNotToPerformAssertions();

        // when
        $this->compilerPass->process($container);
    }

    public function testShouldThrowExceptionOnNonExistingServices(): void
    {
        // given
        $container = new ContainerBuilder();
        $container->setParameter(
            'pk_eager_resettable_services.services',
            ['service_1_id', 'services_2_id',  'services_3_id']
        );
        $container->setDefinition('service_1_id', new Definition());
        $this->expectExceptionObject(new InvalidConfigurationException(
            'Missing resettable services for eager initialization (services_2_id, services_3_id).'
        ));

        // when
        $this->compilerPass->process($container);
    }
}
