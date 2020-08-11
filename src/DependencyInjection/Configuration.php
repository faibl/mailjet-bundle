<?php

namespace Faibl\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('faibl_mailjet');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('api')
                    ->children()
                        ->scalarNode('key')
                            ->isRequired()
                            ->end()
                        ->scalarNode('secret')
                            ->isRequired()
                            ->end()
                    ->end()
                ->end()
            ->scalarNode('logger')
            ->end()
            ->arrayNode('error')
                ->children()
                    ->scalarNode('receiver')
                    ->isRequired()
                    ->end()
                ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
