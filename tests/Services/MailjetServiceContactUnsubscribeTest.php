<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Faibl\MailjetBundle\Tests\FixturesUtil;
use Mailjet\Client;

class MailjetServiceContactUnsubscribeTest extends FaiblMailjetBundleTestCase
{
    public function test()
    {
        $expected = [
            'id' => 12345,
            'body' => [
                'IsUnsubscribed' => 'true',
            ],
        ];

        $this->initBundle('default.yaml');

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(['listrecipient', '']),
                $this->equalTo($expected)
            );

        self::getContainer()->set('fbl_mailjet.client.account_1', $clientMock);

        /** @var MailjetService $mailjetService */
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $contactUnsubscribe = FixturesUtil::contactUnsubscribe();

        $mailjetService->contactUnsubscribe($contactUnsubscribe);
    }
 }
