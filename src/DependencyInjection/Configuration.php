<?php

namespace Faibl\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('faibl_mailjet');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('accounts')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('api')
                                ->children()
                                    ->floatNode('version')->defaultValue('3.1')->end()
                                    ->scalarNode('key')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
                                ->end()
                            ->end() // api
                            ->scalarNode('logger')->end()
                            ->scalarNode('delivery_disabled')->end()
                            ->arrayNode('delivery_addresses')
                                ->performNoDeepMerging()
                                ->beforeNormalization()
                                    ->ifArray()
                                        ->then(function ($v) {
                                            return array_filter(array_values($v));
                                        })
                                    ->end()
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->scalarNode('receiver_errors')->end()
                        ->end()
                    ->end()
                ->end()
            // set default values for all accounts
            ->scalarNode('logger')->defaultValue('logger')->end()
            ->scalarNode('delivery_disabled')->defaultFalse()->end()
            ->arrayNode('delivery_addresses')
                ->performNoDeepMerging()
                ->beforeNormalization()
                    ->ifArray()
                        ->then(function ($v) {
                            return array_filter(array_values($v));
                        })
                    ->end()
                ->prototype('scalar')
                ->end()
            ->end()
            ->scalarNode('receiver_errors')->defaultNull()->end()
        ;

        return $treeBuilder;
    }


}
