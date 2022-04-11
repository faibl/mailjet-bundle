<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAddressCollection;
use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTextMailSerializerDuplicateReceiverTest extends FaiblMailjetBundleTestCase
{
    public function test_text_mail_duplicate_receiver_removed()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../../config/default.yaml');
        $serializer = $this->getContainer()->get('fbl_mailjet.serializer.account_1');
        $mail = $this->getTextMailMultipleReceiver();

        $mailNormalized = $serializer->normalize($mail);

        $expected = [
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
                    'Email' => 'receiver3@email.de',
                ]
            ],
            'Bcc' => [
                [
                    'Email' => 'receiver4@email.de',
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
                new MailjetAddressCollection(['receiver1@email.de','receiver3@email.de'])
            )
            ->addReceiversBcc(
                new MailjetAddressCollection(['receiver2@email.de','receiver4@email.de'])
            )
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>')
            ->addAttachment((new MailjetAttachment())
                ->setContent('TEXT')
                ->setContentType('text/plain')
                ->setFilename('content.txt')
            );
    }
}
