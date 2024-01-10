<?php

namespace Faibl\MailjetBundle\Tests;

use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetContactCreateAndSubscribe;
use Faibl\MailjetBundle\Model\MailjetContactUnsubscribe;
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

    public static function contactCreateAndSubscribe(): MailjetContactCreateAndSubscribe
    {
        return (new MailjetContactCreateAndSubscribe())
            ->setListId(12345)
            ->setEmail('new_contact@mail.de')
            ->setName('Contact New')
            ->setCustomProperties([
                'property_1' => 'value_1',
                'property_2' => 'value_2',
            ])
            ->setAction(MailjetContactCreateAndSubscribe::ACTION_ADD_NO_FORCE);
    }

    public static function contactUnsubscribe(): MailjetContactUnsubscribe
    {
        return (new MailjetContactUnsubscribe())
            ->setListId(12345);
    }
 }
