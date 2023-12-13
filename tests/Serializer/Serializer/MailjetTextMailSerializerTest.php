<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTextMailSerializerTest extends FaiblMailjetBundleTestCase
{
    /**
     * @covers MailjetTextMail
     */
    public function test_text_mail()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTextMail();

        $mailNormalized = $serializer->normalize($mail);

        $expected = [
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
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Text-Mail.');
    }

    /**
     * @covers MailjetTextMail
     * @covers delivery addresses
     */
    public function test_text_mail_to_delivery_address()
    {
        $this->initBundle('delivery_addresses.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTextMail();

        $mailNormalized = $serializer->normalize($mail);

        $expected = [
            'To' => [
                [
                    'Email' => 'delivery_address1@mail.de',
                ],
                [
                    'Email' => 'delivery_address2@mail.de',
                ]
            ],
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
        ];

        $this->assertEquals($expected, $mailNormalized, 'Delivery Address defined in config overrides all recipients.');
    }

    /**
     * @covers MailjetTextMail
     * @covers multiple TextMails
     */
    public function test_multiple_text_mails()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mails = [$this->getTextMail(), $this->getTextMail()];

        $mailNormalized = $serializer->normalize($mails);

        $expected = [
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
            ],
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
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Text-Mail.');
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
            ->addAttachment((new MailjetAttachment())
                ->setContent('TEXT')
                ->setContentType('text/plain')
                ->setFilename('content.txt')
            );
    }
}
