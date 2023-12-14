<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetServiceTest extends FaiblMailjetBundleTestCase
{
    public function test_delivery_disabled()
    {
        $this->initBundle('delivery_disabled.yaml');
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $mail = $this->getTextMail();

        $success = $mailjetService->send($mail);

        $this->assertSame(null, $success, 'Test');
    }

    public function test_delivery_disabled_by_default()
    {
        $this->initBundle('delivery_disabled_by_default.yaml');
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $mail = $this->getTextMail();

        $success = $mailjetService->send($mail);

        $this->assertSame(null, $success, 'Test');
    }

    private function getTextMail(): MailjetTextMail
    {
        return (new MailjetTextMail())
            ->setSender((new MailjetAddress('sender@email.de', 'Sender Send')))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->setTextPart('TEXT');
    }
 }
