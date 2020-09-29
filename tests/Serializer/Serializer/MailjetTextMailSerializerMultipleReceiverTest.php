<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAddressCollection;
use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTextMailSerializerMultipleReceiverTest extends FaiblMailjetBundleTestCase
{
    public function testTextMailMultipleReceiver()
    {
        $this->bootFaiblMailjetBundleKernel();
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = $this->getTextMailMultipleReceiver();

        $mailNormalized = $mailjetMailNormalizer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    'To' => [
                        [
                            'Email' => 'receiver1@email.de',
                            'Name' => 'Receiver1 Receive',
                        ],
                        [
                            'Email' => 'receiver2@email.de',
                            'Name' => 'Receiver2 Receive',
                        ]
                    ],
                    'Cc' => [
                        [
                            'Email' => 'receiver_cc1@email.de',
                        ],
                        [
                            'Email' => 'receiver_cc2@email.de',
                        ]
                    ],
                    'Bcc' => [
                        [
                            'Email' => 'receiver_bcc1@email.de',
                        ],
                        [
                            'Email' => 'receiver_bcc2@email.de',
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
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Text-Mail.');
    }

    private function getTextMailMultipleReceiver(): MailjetTextMail
    {
        return (new MailjetTextMail())
            ->setSender((new MailjetAddress('sender@email.de', 'Sender Send')))
            ->addReceivers((new MailjetAddressCollection([
                ['receiver1@email.de', 'Receiver1 Receive'],
                ['receiver2@email.de', 'Receiver2 Receive'],
            ])))
            ->addReceiversCc((new MailjetAddressCollection([
                ['receiver_cc1@email.de'],
                ['receiver_cc2@email.de'],
            ])))
            ->addReceiversBcc((new MailjetAddressCollection([
                ['receiver_bcc1@email.de'],
                ['receiver_bcc2@email.de'],
            ])))
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
