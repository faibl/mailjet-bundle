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
        $definition->setArgument(2, new Reference($config['logger']));

        $definition = $container->getDefinition('Faibl\MailjetBundle\Serializer\Normalizer\MailjetMailNormalizer');
        $definition->setArgument(0, $config['error']['receiver']);
        $definition->setArgument(1, $config['delivery']['address']);

        $definition = $container->getDefinition('Mailjet\Client');
        $definition->setArgument(0, $config['api']['key']);
        $definition->setArgument(1, $config['api']['secret']);
        $definition->setArgument(2, !$config['delivery']['disabled']);
        $definition->setArgument(3, ['version' => sprintf('v%s', $config['api']['version'])]);
    }
}
