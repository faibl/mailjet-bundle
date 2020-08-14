<?php

namespace Faibl\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FaiblMailjetExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $definition = $container->getDefinition('Faibl\MailjetBundle\Services\MailjetService');
        $definition->setArgument(1, new Reference($config['logger']));
        $definition->setArgument(2, $config['api']['key']);
        $definition->setArgument(3, $config['api']['secret']);
        $definition->setArgument(4, $config['api']['version']);
        $definition->setArgument(5, $config['delivery']['disabled']);

        $definition = $container->getDefinition('Faibl\MailjetBundle\Serializer\Normalizer\MailjetMailNormalizer');
        $definition->setArgument(0, $config['error']['receiver']);
        $definition->setArgument(1, $config['delivery']['address']);
    }
}
