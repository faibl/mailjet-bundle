<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Faibl\MailjetBundle\Tests\FixturesUtil;
use Mailjet\Client;

class MailjetServiceContactlistItemUpdateTest extends FaiblMailjetBundleTestCase
{
    public function test()
    {
        $expected = [
            'id' => 12345,
            'body' => [
                'Name' => 'Contact New',
                'Properties' => [
                    'property_1' => 'value_1',
                    'property_2' => 'value_2',
                ],
                'Action' => 'addnoforce',
                'Email' => 'new_contact@mail.de',
            ],
        ];

        $this->initBundle('default.yaml');

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(['contactslist', 'ManageContact']),
                $this->equalTo($expected),
                $this->equalTo([
                    'version' => 'v3',
                    'call' => true,
                    ])
            );

        self::getContainer()->set('fbl_mailjet.client.account_1', $clientMock);

        /** @var MailjetService $mailjetService */
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $contactCreateAndSubscribe = FixturesUtil::contactlistItemUpdate();

        $mailjetService->contactlistItemUpdate($contactCreateAndSubscribe);
    }

    public function test_diabled()
    {
        $this->initBundle('delivery_disabled.yaml');

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->equalTo([
                    'version' => 'v3',
                    'call' => false,
                    ])
            );

        self::getContainer()->set('fbl_mailjet.client.account_1', $clientMock);

        /** @var MailjetService $mailjetService */
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $contactCreateAndSubscribe = FixturesUtil::contactlistItemUpdate();

        $mailjetService->contactlistItemUpdate($contactCreateAndSubscribe);
    }
 }
