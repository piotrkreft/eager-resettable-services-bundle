<?php

declare(strict_types=1);

namespace PK\Tests\EagerResettableServicesBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use PK\EagerResettableServicesBundle\DependencyInjection\PKEagerResettableServicesExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PKEagerResettableServicesExtensionTest extends TestCase
{
    /**
     * @var PKEagerResettableServicesExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new PKEagerResettableServicesExtension();
    }

    public function testShouldLoad(): void
    {
        // given
        $configuration = [
            'pk_eager_resettable_services' => [
                'all_services' => false,
                'services' => ['service_id'],
            ],
        ];
        $container = new ContainerBuilder();

        // when
        $this->extension->load($configuration, $container);

        // then
        $this->assertEquals(['service_id'], $container->getParameter('pk_eager_resettable_services.services'));
        $this->assertEquals(false, $container->getParameter('pk_eager_resettable_services.all_services'));
    }
}
