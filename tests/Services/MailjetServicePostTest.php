<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Mailjet\Client;

class MailjetServicePostTest extends FaiblMailjetBundleTestCase
{
    public function test()
    {
        $resource = ['contactslist', 'ManageContact'];
        $args = [
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
        $options = [
            'version' => 'v3',
            'call' => false,
        ];

        $this->initBundle('default.yaml');

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo($resource),
                $this->equalTo($args),
                $this->equalTo($options)
            );

        self::getContainer()->set('fbl_mailjet.client.account_1', $clientMock);

        /** @var MailjetService $mailjetService */
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');

        $mailjetService->post($resource, $args, $options);
    }
 }
