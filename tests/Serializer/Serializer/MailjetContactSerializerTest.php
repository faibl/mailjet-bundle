<?php

namespace Faibl\MailjetBundle\Tests\Serializer\Serializer;

use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;
use Faibl\MailjetBundle\Tests\FixturesUtil;

class MailjetContactSerializerTest extends FaiblMailjetBundleTestCase
{
    public function test_contact_create_and_subscribe()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $contactCreateAndSubscribe = FixturesUtil::contactCreateAndSubscribe();
        $normalized = $serializer->normalize($contactCreateAndSubscribe);

        $expected = [
            'Name' => 'Contact New',
            'Properties' => [
                'property_1' => 'value_1',
                'property_2' => 'value_2',
            ],
            'Action' => 'addnoforce',
            'Email' => 'new_contact@mail.de',
        ];

        $this->assertEquals($expected, $normalized, 'Normalize Contact-Create-And-Subscribe.');
    }

    public function test_contact_unsubscribe()
    {
        $this->initBundle('default.yaml');
        $serializer = self::getContainer()->get('fbl_mailjet.serializer.account_1');
        $contactUnsubscribe = FixturesUtil::contactUnsubscribe();
        $normalized = $serializer->normalize($contactUnsubscribe);

        $expected = [
            'IsUnsubscribed' => 'true',
        ];

        $this->assertEquals($expected, $normalized, 'Normalize Contact-Unsubscribe.');
    }
}
