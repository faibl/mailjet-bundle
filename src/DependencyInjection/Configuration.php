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
                                    ->scalarNode('version')->defaultValue('3.1')->end()
                                    ->scalarNode('key')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
                                ->end()
                            ->end() // api
                            ->scalarNode('logger')->defaultValue('logger')->end()
                            ->scalarNode('delivery_disabled')->defaultFalse()->end()
                            ->scalarNode('delivery_address')->defaultNull()->end()
                            ->scalarNode('receiver_errors')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
