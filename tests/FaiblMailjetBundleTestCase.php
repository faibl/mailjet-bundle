<?php

namespace Faibl\MailjetBundle\Tests;

use Faibl\MailjetBundle\FaiblMailjetBundle;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;

class FaiblMailjetBundleTestCase extends BaseBundleTestCase
{
    protected function getBundleClass()
    {
        return FaiblMailjetBundle::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Make services public that have an id that matches a regex
        $this->addCompilerPass(new PublicServicePass('|Faibl\\\MailjetBundle\\\*|'));
    }

    protected function bootFaiblMailjetBundleKernel(string $configFile = null): void
    {
        self::bootKernel();
        $kernel = $this->createKernel();
        if ($configFile) {
            $kernel->addConfigFile($configFile);
        }

        $this->bootKernel();
    }

    protected function getService(string $class)
    {
        return $this->getContainer()->get($class);
    }
}
