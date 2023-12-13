<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Services\MailjetServiceLocator;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetServiceLocatorTest extends FaiblMailjetBundleTestCase
{
    /**
     * @covers MailjetServiceLocator
     */
    public function test_service_getter()
    {
        $this->initBundle('multiple_accounts.yaml');
        /** @var MailjetServiceLocator $mailjetServiceFactory */
        $mailjetServiceFactory = self::getContainer()->get(MailjetServiceLocator::class);

        $mailjetService1_1 = $mailjetServiceFactory->getService('account_1');
        $mailjetService1_2 = self::getContainer()->get('fbl_mailjet.service.account_1');

        $mailjetService2_1 = $mailjetServiceFactory->getService('account_2');
        $mailjetService2_2 = self::getContainer()->get('fbl_mailjet.service.account_2');

        $this->assertInstanceOf(MailjetService::class, $mailjetService1_1);
        $this->assertInstanceOf(MailjetService::class, $mailjetService2_1);

        $this->assertSame($mailjetService1_1, $mailjetService1_2);
        $this->assertSame($mailjetService2_1, $mailjetService2_2);

        $this->assertNotSame($mailjetService1_1, $mailjetService2_1);
    }

    //public function test_unknown_service()
    //{
    //    $this->initBundle('multiple_accounts.yaml');
    //    /** @var MailjetServiceLocator $mailjetServiceFactory */
    //    $mailjetServiceFactory = self::getContainer()->get(MailjetServiceLocator::class);
    //
    //    $unknownService = $mailjetServiceFactory->getService('account_3');
    //    $this->assertEquals(null, $unknownService);
    //
    //    $this->expectException('Exception');
    //    $mailjetServiceFactory->send('account_3', new MailjetTextMail());
    //}
 }
