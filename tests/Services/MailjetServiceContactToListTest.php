<?php

namespace Faibl\MailjetBundle\Tests\Services;

use Faibl\MailjetBundle\Model\MailjetContactToList;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Tests\FaiblMailjetBundleTestCase;

class MailjetServiceContactToListTest extends FaiblMailjetBundleTestCase
{
    //public function test_delivery_disabled()
    //{
    //    $this->initBundle('default.yaml');
    //    /** @var MailjetService $mailjetService */
    //    $mailjetService = self::getContainer()->get('fbl_mailjet.service.account_1');
    //    $contactToList = $this->getContactToList();
    //
    //    $success = $mailjetService->createContactAndAddToList($contactToList);
    //
    //    $this->assertSame(null, $success, 'Test');
    //}

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
