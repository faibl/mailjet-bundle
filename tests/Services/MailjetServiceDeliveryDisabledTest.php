<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Faibl\MailjetBundle\Tests\FixturesUtil;
use Faibl\MailjetBundle\Tests\ReflectionUtil;

class MailjetServiceDeliveryDisabledTest extends FaiblMailjetBundleTestCase
{
    public function test_delivery_disabled()
    {
        $this->initBundle('delivery_disabled.yaml');
        $client = self::getContainer()->get('fbl_mailjet.client.account_1');
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');

        $this->assertFalse(ReflectionUtil::getProperty($client, 'call'));
        $this->assertNull($mailjetService->send(FixturesUtil::textMail()));
    }

    public function test_delivery_disabled_by_default()
    {
        $this->initBundle('delivery_disabled_by_default.yaml');
        $client = self::getContainer()->get('fbl_mailjet.client.account_1');
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');

        $this->assertFalse(ReflectionUtil::getProperty($client, 'call'));
        $this->assertNull($mailjetService->send(FixturesUtil::textMail()));
    }
 }
