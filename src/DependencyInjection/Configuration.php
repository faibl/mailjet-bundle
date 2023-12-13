<?php

namespace Faibl\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
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
                            ->booleanNode('delivery_enabled')->end()
                            ->scalarNode('delivery_addresses')->end()
                            ->scalarNode('receiver_errors')->end()
                        ->end()
                    ->end()
                ->end()
            // set default values for all accounts
            ->scalarNode('logger')->defaultValue('logger')->end()
            ->booleanNode('delivery_enabled')->defaultFalse()->end()
            ->scalarNode('delivery_addresses')->defaultNull()->end()
            ->scalarNode('receiver_errors')->defaultNull()->end()
        ;

        return $treeBuilder;
    }


}
