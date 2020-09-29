<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetServiceTest extends FaiblMailjetBundleTestCase
{
    public function testDeliveryDisabled()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../config/delivery_disabled.yaml');
        $mailjetService = $this->getMailjetService();
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

    private function getMailjetService(): MailjetService
    {
        return $this->getContainer()->get(MailjetService::class);
    }
 }
