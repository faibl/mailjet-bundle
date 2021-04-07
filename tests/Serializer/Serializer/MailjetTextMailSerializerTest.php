<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTextMailSerializerTest extends FaiblMailjetBundleTestCase
{
    public function test_text_mail()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../../config/default.yaml');
        $serializer = $this->getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTextMail();

        $mailNormalized = $serializer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    'To' => [[
                        'Email' => 'receiver@email.de',
                        'Name' => 'Receiver Receive',
                    ]],
                    'Cc' => [[
                        'Email' => 'receiver_cc@email.de',
                        'Name' => 'ReceiverCc Receive',
                    ]],
                    'Bcc' => [[
                        'Email' => 'receiver_bcc@email.de',
                        'Name' => 'ReceiverBcc Receive',
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

    public function test_text_mail_to_delivery_address()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../../config/delivery_address.yaml');
        $serializer = $this->getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTextMail();

        $mailNormalized = $serializer->normalize($mail);

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
            ->addReceiverCc((new MailjetAddress('receiver_cc@email.de', 'ReceiverCc Receive')))
            ->addReceiverBcc((new MailjetAddress('receiver_bcc@email.de', 'ReceiverBcc Receive')))
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>')
            ->setAttachment((new MailjetAttachment())
                ->setContent('TEXT')
                ->setContentType('text/plain')
                ->setFilename('content.txt')
            );
    }
}
