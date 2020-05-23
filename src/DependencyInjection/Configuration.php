<?php

declare(strict_types=1);

namespace PK\EagerResettableServicesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pk_eager_resettable_services');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->arrayNode('services')
                        ->scalarPrototype()->end()
                    ->end()
                    ->booleanNode('all_services')->defaultFalse()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
