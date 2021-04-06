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
        $serializer = $this->getContainer()->get(MailjetMailSerializer::class);
        $mail = $this->getTextMailMultipleReceiver();

        $mailNormalized = $serializer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    'To' => [
                        [
                            'Email' => 'receiver1@email.de',
                        ],
                        [
                            'Email' => 'receiver2@email.de',
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
            ->addReceivers(
                new MailjetAddressCollection(['receiver1@email.de','receiver2@email.de'])
            )
            ->addReceiversCc(
                new MailjetAddressCollection(['receiver_cc1@email.de','receiver_cc2@email.de'])
            )
            ->addReceiversBcc(
                new MailjetAddressCollection(['receiver_bcc1@email.de','receiver_bcc2@email.de'])
            )
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>')
            ->setAttachment((new MailjetAttachment())
                ->setContent('TEXT')
                ->setContentType('text/plain')
                ->setFilename('content.txt')
            );
    }
}
