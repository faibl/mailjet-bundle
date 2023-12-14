<?php

namespace Faibl\MailjetBundle\DependencyInjection;

use Faibl\MailjetBundle\Serializer\Normalizer\MailjetContactToListNormalizer;
use Faibl\MailjetBundle\Serializer\Normalizer\MailjetMailNormalizer;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Services\MailjetServiceLocator;
use Mailjet\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class FaiblMailjetExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setDefinition(MailjetContactToListNormalizer::class, new Definition(MailjetContactToListNormalizer::class));

        foreach ($config['accounts'] as $name => $accountConfig) {
            $this->registerServices(
                $container,
                $name,
                $this->mergeAccountAndGlobalConfig($accountConfig, $config)
            );
        }
    }

    private function mergeAccountAndGlobalConfig(array $accountConfig, array $config): array
    {
        unset($config['accounts']);
        // override global with account-config
        return array_merge($config, $accountConfig);
    }

    public function registerServices(ContainerBuilder $container, string $name, array $config): void
    {
        $clientId = sprintf('fbl_mailjet.client.%s', $name);
        $client = (new Definition(Client::class))
            ->setArgument(0, $config['api']['key'])
            ->setArgument(1, $config['api']['secret'])
            ->setArgument(2, $config['delivery_enabled']) // this prevents api to call mailjet
            ->setArgument(3, ['version' => sprintf('v%s', $config['api']['version'])])
            ->setPublic(true);
        $container->setDefinition($clientId, $client);

        $normalizerId = sprintf('fbl_mailjet.normalizer.%s', $name);
        $normalizer = (new Definition(MailjetMailNormalizer::class))
            ->setArgument(0, $config['receiver_errors'])
            ->setArgument(1, $config['delivery_addresses'])
            ->setPublic(true);
        $container->setDefinition($normalizerId, $normalizer);

        $serializerId = sprintf('fbl_mailjet.serializer.%s', $name);
        $serializer = (new Definition(MailjetMailSerializer::class))
            ->setArgument(0, new Reference($normalizerId))
            ->setArgument(1, new Reference(MailjetContactToListNormalizer::class))
            ->setArgument(2, new Reference('serializer.encoder.json'))
            ->setPublic(true);
        $container->setDefinition($serializerId, $serializer);

        $serviceId = sprintf('fbl_mailjet.service.%s', $name);
        $service = (new Definition(MailjetService::class))
            ->setArgument(0, new Reference($clientId))
            ->setArgument(1, new Reference($serializerId))
            ->setArgument(2, new Reference($config['logger']))
            ->setPublic(true);
        $container->setDefinition($serviceId, $service);

        $container->getDefinition(MailjetServiceLocator::class)
            ->addMethodCall('addService', [$name, new Reference($serviceId)]);
    }
}
