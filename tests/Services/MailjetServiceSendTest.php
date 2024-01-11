<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Faibl\MailjetBundle\Tests\FixturesUtil;
use Mailjet\Client;

class MailjetServiceSendTest extends FaiblMailjetBundleTestCase
{
    public function test_text_mail()
    {
        $expected = [
            'body' => [
                'Messages' => [
                    [
                        'To' => [[
                            'Email' => 'receiver@email.de',
                            'Name' => 'Receiver Receive',
                        ]],
                        'From' => [
                            'Email' => 'sender@email.de',
                            'Name' => 'Sender Send',
                        ],
                        'TextPart' => 'TEXT',
                    ]
                ],
                'SandboxMode' => false,
            ],
        ];

        $this->initBundle('default.yaml');

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(['send', '']),
                $this->equalTo($expected)
            );

        self::getContainer()->set('fbl_mailjet.client.account_1', $clientMock);

        /** @var MailjetService $mailjetService */
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $mail = FixturesUtil::textMail();

        $mailjetService->send($mail);
    }

    public function test_text_mail_sandbox()
    {
        $expected = [
            'body' => [
                'Messages' => [
                    [
                        'To' => [[
                            'Email' => 'receiver@email.de',
                            'Name' => 'Receiver Receive',
                        ]],
                        'From' => [
                            'Email' => 'sender@email.de',
                            'Name' => 'Sender Send',
                        ],
                        'TextPart' => 'TEXT',
                    ]
                ],
                'SandboxMode' => true,
            ],
        ];

        $this->initBundle('default.yaml');

        $clientMock = $this->createMock(Client::class);
        $clientMock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(['send', '']),
                $this->equalTo($expected)
            );

        self::getContainer()->set('fbl_mailjet.client.account_1', $clientMock);

        /** @var MailjetService $mailjetService */
        $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
        $mail = FixturesUtil::textMail();

        $mailjetService->send($mail, true);
    }
 }
