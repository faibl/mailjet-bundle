<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Services\MailjetServiceLocator;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Faibl\MailjetBundle\Tests\FixturesUtil;

class MailjetServiceLocatorTest extends FaiblMailjetBundleTestCase
{
    public function test_multiple_services()
    {
        $this->initBundle('multiple_accounts.yaml');

        $service1Mock = $this->createMock(MailjetService::class);
        $service1Mock->expects($this->once())
            ->method('send')
            ->with(
                $this->anything()
            );

        self::getContainer()->set('fbl_mailjet.service.account_1', $service1Mock);

        $service2Mock = $this->createMock(MailjetService::class);
        $service2Mock->expects($this->never())
            ->method('send')
            ->with(
                $this->anything()
            );

        self::getContainer()->set('fbl_mailjet.service.account_2', $service2Mock);

        /** @var MailjetServiceLocator $mailjetServiceFactory */
        $mailjetServiceFactory = self::getContainer()->get(MailjetServiceLocator::class);
        $mail = FixturesUtil::textMail();

        $mailjetServiceFactory->send('account_1', $mail);
    }
 }
