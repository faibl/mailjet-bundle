<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTextMailSerializerSandboxModeTest extends FaiblMailjetBundleTestCase
{
    public function testTextMail()
    {
        $this->bootFaiblMailjetBundleKernel();
        $serializer = $this->getContainer()->get(MailjetMailSerializer::class);
        $mail = $this->getTextMail();

        $mailNormalized = $serializer->normalize($mail);

        $expected =  [
            "Messages" => [
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
                    'HtmlPart' => '<p>HTML</p>',
                    'SandboxMode' => true,
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
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>')
            ->setSandboxMode(true);
    }
}
