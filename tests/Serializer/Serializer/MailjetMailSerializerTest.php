<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\FaiblMailjetBundle;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;

class MailjetMailSerializerTest extends BaseBundleTestCase
{
    protected function getBundleClass()
    {
        return FaiblMailjetBundle::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Make services public that have an id that matches a regex
        $this->addCompilerPass(new PublicServicePass('|Faibl\\\MailjetBundle\\\*|'));
    }

    public function testServiceExists()
    {
        $service = $this->getSerializer();
        $this->assertInstanceOf(MailjetMailSerializer::class, $service, sprintf('Normalizer is of type %s', MailjetMailSerializer::class));
    }

    public function testTextMail()
    {
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = (new MailjetTextMail())
            ->setSender((new MailjetAddress('sender@email.de', 'Sender Send')))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->setTextPart('TEXT')
            ->setHtmlPart('<p>HTML</p>');
        $mailNormalized = $mailjetMailNormalizer->normalize($mail);

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
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail to array');
    }

    public function testTemplateMail()
    {
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = (new MailjetTemplateMail(123))
            ->setVariables(['key1' => 'val1', 'key2' => ['val2.1' => 'key2.1']]);
        $mailNormalized = $mailjetMailNormalizer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    "TemplateLanguage" => true,
                    "TemplateID" => 123,
                    "Variables" =>  [
                        "key1" => "val1",
                        "key2" =>  [
                            "val2.1" => "key2.1",
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail to array');
    }

    private function getSerializer(): MailjetMailSerializer
    {
        self::bootKernel();
        $this->bootKernel();
        $container = $this->getContainer();

        return $container->get('Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer');
    }
}
