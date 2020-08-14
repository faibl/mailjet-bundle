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
                ->arrayNode('api')
                    ->children()
                        ->scalarNode('version')
                            ->defaultValue('3.1')
                        ->end()
                        ->scalarNode('key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end() // api
                ->scalarNode('logger')
                ->end() // logger
                ->arrayNode('delivery')
                    ->children()
                        ->scalarNode('disabled')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('address')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end() // delivery
                ->arrayNode('error')
                    ->children()
                        ->scalarNode('receiver')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end() // error
            ->end();

        return $treeBuilder;
    }
}
