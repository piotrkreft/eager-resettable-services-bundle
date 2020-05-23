<?php

declare(strict_types=1);

namespace PK\Tests\EagerResettableServicesBundle\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use PK\EagerResettableServicesBundle\DependencyInjection\Compiler\EagerResettableServicesPass;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class EagerResettableServicesPassTest extends TestCase
{
    /**
     * @var EagerResettableServicesPass
     */
    private $compilerPass;

    protected function setUp(): void
    {
        $this->compilerPass = new EagerResettableServicesPass();
    }

    public function testShouldSkipWhenNoResetter(): void
    {
        // given
        $container = new ContainerBuilder();
        $container->setParameter('pk_eager_resettable_services.services', []);
        $container->setParameter('pk_eager_resettable_services.all_services', true);

        // when
        $this->compilerPass->process($container);

        // then
        $this->assertFalse($container->hasDefinition('services_resetter'));
    }

    /**
     * @dataProvider casesProvider
     *
     * @param Reference[] $expectedArguments
     */
    public function testShouldOverrideServicesArgument(
        ContainerBuilder $container,
        array $expectedArguments
    ): void {
        // when
        $this->compilerPass->process($container);

        // then
        $this->assertEquals(
            $expectedArguments,
            $container->getDefinition('services_resetter')->getArgument(0)->getValues()
        );
    }

    /**
     * @return mixed[][]
     */
    public function casesProvider(): array
    {
        return [
            'no eager loaded' => [
                $this->buildContainer()
                    ->setParameter('pk_eager_resettable_services.services', [])
                    ->setParameter('pk_eager_resettable_services.all_services', false),
                [
                    'definition1' => new Reference(
                        'definition1',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                    'definition2' => new Reference(
                        'definition2',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                    'definition3' => new Reference(
                        'definition3',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                ],
            ],
            'one eager loaded' => [
                $this->buildContainer()
                    ->setParameter('pk_eager_resettable_services.services', ['definition1'])
                    ->setParameter('pk_eager_resettable_services.all_services', false),
                [
                    'definition1' => new Reference('definition1'),
                    'definition2' => new Reference(
                        'definition2',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                    'definition3' => new Reference(
                        'definition3',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                ],
            ],
            'all eager loaded' => [
                $this->buildContainer()
                    ->setParameter('pk_eager_resettable_services.services', [])
                    ->setParameter('pk_eager_resettable_services.all_services', true),
                [
                    'definition1' => new Reference('definition1'),
                    'definition2' => new Reference(
                        'definition2',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                    'definition3' => new Reference('definition3'),
                ],
            ],
            'all eager loaded with excessive configuration' => [
                $this->buildContainer()
                    ->setParameter('pk_eager_resettable_services.services', ['definition1'])
                    ->setParameter('pk_eager_resettable_services.all_services', true),
                [
                    'definition1' => new Reference('definition1'),
                    'definition2' => new Reference(
                        'definition2',
                        ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE
                    ),
                    'definition3' => new Reference('definition3'),
                ],
            ],
        ];
    }

    private function buildContainer(): ContainerBuilder
    {
        $definition1 = (new Definition());
        $definition3 = (new Definition());
        $resettableServicesArgument = new IteratorArgument([
            'definition1' => new Reference('definition1', ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE),
            'definition2' => new Reference('definition2', ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE),
            'definition3' => new Reference('definition3', ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE),
        ]);
        $servicesResetter = (new Definition())->setArgument(0, $resettableServicesArgument);
        $container = new class () extends ContainerBuilder {
            /**
             * {@inheritdoc}
             */
            public function setParameter($name, $value)
            {
                parent::setParameter($name, $value);

                return $this;
            }
        };
        $container->addDefinitions([
            'definition1' => $definition1,
            'definition3' => $definition3,
            'services_resetter' => $servicesResetter,
        ]);

        return $container;
    }
}
