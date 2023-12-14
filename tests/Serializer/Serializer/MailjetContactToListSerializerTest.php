<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetContactToList;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetContactToListSerializerTest extends FaiblMailjetBundleTestCase
{
    public function test_template_mail()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $contactToList = $this->getContactToList();
        $mailNormalized = $serializer->normalize($contactToList);

        $expected = [
            'Name' => 'Contact New',
            'Properties' => [
                'property_1' => 'value_1',
                'property_2' => 'value_2',
            ],
            'Action' => 'addforce',
            'Email' => 'new_contact@mail.de',
        ];

        $this->assertEquals($expected, $mailNormalized, 'Normalize Template-Mail.');
    }

    private function getContactToList(): MailjetContactToList
    {
        return (new MailjetContactToList())
            ->setListId(12345)
            ->setEmail('new_contact@mail.de')
            ->setName('Contact New')
            ->setCustomProperties([
                'property_1' => 'value_1',
                'property_2' => 'value_2',
            ])
            ->setAction(MailjetContactToList::ACTION_ADD_FORCE);
    }
}
