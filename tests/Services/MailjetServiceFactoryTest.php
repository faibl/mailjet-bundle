<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Services\MailjetServiceFactory;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetServiceFactoryTest extends FaiblMailjetBundleTestCase
{
    public function test_service_getter()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__ . '/../config/multiple_accounts.yaml');
        /** @var MailjetServiceFactory $mailjetServiceFactory */
        $mailjetServiceFactory = $this->getContainer()->get(MailjetServiceFactory::class);

        $mailjetService1_1 = $mailjetServiceFactory->getService('account_1');
        $mailjetService1_2 = $this->getContainer()->get('fbl_mailjet.service.account_1');

        $mailjetService2_1 = $mailjetServiceFactory->getService('account_2');
        $mailjetService2_2 = $this->getContainer()->get('fbl_mailjet.service.account_2');

        $this->assertInstanceOf(MailjetService::class, $mailjetService1_1);
        $this->assertInstanceOf(MailjetService::class, $mailjetService2_1);

        $this->assertSame($mailjetService1_1, $mailjetService1_2);
        $this->assertSame($mailjetService2_1, $mailjetService2_2);

        $this->assertNotSame($mailjetService1_1, $mailjetService2_1);
    }

    public function test_unknown_service()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__ . '/../config/multiple_accounts.yaml');
        /** @var MailjetServiceFactory $mailjetServiceFactory */
        $mailjetServiceFactory = $this->getContainer()->get(MailjetServiceFactory::class);

        $unknownService = $mailjetServiceFactory->getService('account_3');
        $this->assertEquals(null, $unknownService);

        $this->expectException('Exception');
        $mailjetServiceFactory->send('account_3', new MailjetTextMail());
    }
 }
