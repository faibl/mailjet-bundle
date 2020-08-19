<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetTemplateMailSerializerTest extends FaiblMailjetBundleTestCase
{
    public function testTemplateMail()
    {
        $this->bootFaiblMailjetBundleKernel();
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = $this->getTemplateMail();
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

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail.');
    }

    public function testTextMailToDeliveryAddress()
    {
        $this->bootFaiblMailjetBundleKernel(__DIR__.'/../../config/delivery_address.yaml');
        $mailjetMailNormalizer = $this->getSerializer();
        $mail = $this->getTemplateMail();
        $mailNormalized = $mailjetMailNormalizer->normalize($mail);

        $expected =  [
            "Messages" => [
                [
                    'To' => [[
                        'Email' => 'delivery_address@mail.de',
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
            ]
        ];

        $this->assertEquals($expected, $mailNormalized, 'Delivery Address defined in config overrides all recipients.');
    }

    private function getTemplateMail(): MailjetTemplateMail
    {
        return (new MailjetTemplateMail(123))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->addReceiverCC((new MailjetAddress('receiver_cc@email.de', 'ReceiverCC Receive')))
            ->addReceiverBC((new MailjetAddress('receiver_bc@email.de', 'ReceiverBC Receive')))
            ->setVariables(['key1' => 'val1', 'key2' => ['val2.1' => 'key2.1']]);
    }

    private function getSerializer(): MailjetMailSerializer
    {
        return $this->getContainer()->get(MailjetMailSerializer::class);
    }

}
