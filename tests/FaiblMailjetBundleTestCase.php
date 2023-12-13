<?php

namespace Faibl\MailjetBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Nyholm\BundleTest\TestKernel;
use Faibl\MailjetBundle\FaiblMailjetBundle;
use Symfony\Component\HttpKernel\KernelInterface;

class FaiblMailjetBundleTestCase extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var TestKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(FaiblMailjetBundle::class);
        $kernel->handleOptions($options);

        return $kernel;
    }

    protected function initBundle(string $configFile): void
    {
        self::bootKernel(['config' => static function(TestKernel $kernel) use ($configFile) {
            // Add some configuration
            $kernel->addTestConfig(sprintf('%s/config/%s', __DIR__, $configFile));
        }]);
    }
}
