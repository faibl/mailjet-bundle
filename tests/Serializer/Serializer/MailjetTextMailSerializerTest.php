<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTextMailSerializerTest extends FaiblMailjetBundleTestCase
{
    public function testTextMail()
    {
        $this->bootFaiblMailjetBundleKernel();
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = $this->getTextMail();

        $mailNormalized = $mailjetMailNormalizer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    'To' => [[
                        'Email' => 'receiver@email.de',
                        'Name' => 'Receiver Receive',
                    ]],
                    'Cc' => [[
                        'Email' => 'receiver_cc@email.de',
                        'Name' => 'ReceiverCC Receive',
                    ]],
                    'Bc' => [[
                        'Email' => 'receiver_bc@email.de',
                        'Name' => 'ReceiverBC Receive',
                    ]],
                    'From' => [
                        'Email' => 'sender@email.de',
                        'Name' => 'Sender Send',
                    ],
                    'TextPart' => 'TEXT',
                    'HtmlPart' => '<p>HTML</p>',
                    'Attachments' => [
                        [
                            'ContentType' => 'text/plain',
                            'Filename' => 'content.txt',
                            'Base64Content' => base64_encode('TEXT')
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Text-Mail.');
    }

    public function testTextMailToDeliveryAddress()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../../config/delivery_address.yaml');
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = $this->getTextMail();

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
                    'Attachments' => [
                        [
                            'ContentType' => 'text/plain',
                            'Filename' => 'content.txt',
                            'Base64Content' => base64_encode('TEXT')
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Delivery Address defined in config overrides all recipients.');
    }

    private function getTextMail(): MailjetTextMail
    {
        return (new MailjetTextMail())
            ->setSender((new MailjetAddress('sender@email.de', 'Sender Send')))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->addReceiverCC((new MailjetAddress('receiver_cc@email.de', 'ReceiverCC Receive')))
            ->addReceiverBC((new MailjetAddress('receiver_bc@email.de', 'ReceiverBC Receive')))
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>')
            ->setAttachment((new MailjetAttachment())
                ->setContent('TEXT')
                ->setContentType('text/plain')
                ->setFilename('content.txt')
            );
    }

    private function getSerializer(): MailjetMailSerializer
    {
        return $this->getContainer()->get(MailjetMailSerializer::class);
    }
}
