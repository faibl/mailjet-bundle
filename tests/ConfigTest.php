<?php

namespace Faibl\MailjetBundle\Tests;

use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;

class ConfigTest extends FaiblMailjetBundleTestCase
{
    public function testTextMail()
    {
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = (new MailjetTextMail())
            ->setSender((new MailjetAddress('sender@email.de', 'Sender Send')))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->addReceiverCC((new MailjetAddress('receiver_cc@email.de', 'ReceiverCC Receive')))
            ->addReceiverBC((new MailjetAddress('receiver_bc@email.de', 'ReceiverBC Receive')))
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>');
        $mailNormalized = $mailjetMailNormalizer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    'To' => [[
                        'Email' => 'delivery_address@mail.de',
                    ]],
                    'From' => [
                        'Email' => 'sender@email.de',
                        'Name' => 'Sender Send',
                    ],
                    'TextPart' => 'TEXT',
                    'HtmlPart' => '<p>HTML</p>',
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail to array');
    }

    private function getSerializer(): MailjetMailSerializer
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/config/faibl_mailjet.yaml');

        $container = $this->getContainer();

        return $container->get('Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer');
    }
}
