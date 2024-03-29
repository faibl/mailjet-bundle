<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTemplateMailSerializerTest extends FaiblMailjetBundleTestCase
{
    /**
     * @covers MailjetTemplateMail
     */
    public function test_template_mail()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTemplateMail();
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
            "TemplateLanguage" => true,
            "TemplateID" => 123,
            "Variables" =>  [
                "key1" => "val1",
                "key2" =>  [
                    "val2.1" => "key2.1",
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail.');
    }

    /**
     * @covers MailjetTextMail
     * @covers delivery_addresses
     */
    public function test_text_mail_to_delivery_addresses()
    {
        $this->initBundle('delivery_addresses.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTemplateMail();
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
            "TemplateLanguage" => true,
            "TemplateID" => 123,
            "Variables" =>  [
                "key1" => "val1",
                "key2" =>  [
                    "val2.1" => "key2.1",
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Delivery Address defined in config overrides all recipients.');
    }

    /**
     * @covers MailjetTextMail
     * @covers delivery addresses by default
     */
    public function test_text_mail_to_delivery_addresses_by_default()
    {
        $this->initBundle('delivery_addresses_by_default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTemplateMail();
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
                    "TemplateLanguage" => true,
                    "TemplateID" => 123,
                    "Variables" =>  [
                        "key1" => "val1",
                        "key2" =>  [
                            "val2.1" => "key2.1",
                        ]
                    ]
                ];

        $this->assertEquals($expected, $mailNormalized, 'Delivery Address defined in config overrides all recipients.');
    }

    /**
     * @covers MailjetTemplateMail
     * @covers multiple
     */
    public function test_multiple_template_mail()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $mails = [
            $this->getTemplateMail(),
            $this->getTemplateMail()
        ];
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
                "TemplateLanguage" => true,
                "TemplateID" => 123,
                "Variables" =>  [
                    "key1" => "val1",
                    "key2" =>  [
                        "val2.1" => "key2.1",
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
                "TemplateLanguage" => true,
                "TemplateID" => 123,
                "Variables" =>  [
                    "key1" => "val1",
                    "key2" =>  [
                        "val2.1" => "key2.1",
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail.');
    }

    private function getTemplateMail(): MailjetTemplateMail
    {
        return (new MailjetTemplateMail(123))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->addReceiverCc((new MailjetAddress('receiver_cc@email.de', 'ReceiverCc Receive')))
            ->addReceiverBcc((new MailjetAddress('receiver_bcc@email.de', 'ReceiverBcc Receive')))
            ->setVariables(['key1' => 'val1', 'key2' => ['val2.1' => 'key2.1']]);
    }
}
