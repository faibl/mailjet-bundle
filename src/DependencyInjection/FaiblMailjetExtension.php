<?php

namespace Faibl\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FaiblMailjetExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('Faibl\MailjetBundle\Services\MailjetService');
        $definition->setArgument(1, new Reference($config['logger']));
        $definition->setArgument(2, $config['api']['key']);
        $definition->setArgument(3, $config['api']['secret']);
        $definition->setArgument(4, $config['error']['receiver']);


//        $definition = $container->getDefinition('Faibl\MailjetBundle\Search\Manager\SearchClient');
//        $definition->setArgument(0, new Reference($config['logger']));
//
//        $definition = $container->getDefinition('Faibl\MailjetBundle\Search\Manager\SearchManager');
//        $definition->setArgument(1, $config['mailjet']);
//
//
//        $definition = $container->getDefinition('Faibl\MailjetBundle\Command\SearchIndexCommand');
//        $definition->setArgument(2, $config['entity']['class']);
    }
}
