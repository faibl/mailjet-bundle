<?php

namespace Faibl\MailjetBundle\Tests;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetContactlistItemUpdate;
use Faibl\MailjetBundle\Model\MailjetTextMail;

class FixturesUtil
{
    public static function textMail(): MailjetTextMail
    {
        return (new MailjetTextMail())
            ->setSender((new MailjetAddress('sender@email.de', 'Sender Send')))
            ->addReceiver((new MailjetAddress('receiver@email.de', 'Receiver Receive')))
            ->setTextPart('TEXT');
    }

    public static function contactlistItemUpdate(): MailjetContactlistItemUpdate
    {
        return (new MailjetContactlistItemUpdate())
            ->setListId(12345)
            ->setEmail('new_contact@mail.de')
            ->setName('Contact New')
            ->setCustomProperties([
                'property_1' => 'value_1',
                'property_2' => 'value_2',
            ])
            ->setAction(MailjetContactlistItemUpdate::ACTION_ADD_NO_FORCE);
    }

    public static function contactlistItemUnsubscribe(): MailjetContactlistItemUpdate
    {
        return (new MailjetContactlistItemUpdate())
            ->setListId(12345)
            ->setEmail('new_contact@mail.de')
            ->setAction(MailjetContactlistItemUpdate::ACTION_UNSUBSCRIBE);
    }
 }
