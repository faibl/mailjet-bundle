<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Model\MailjetMail;

class MailjetMockService extends MailjetService
{
    public function send(MailjetMail $mail): bool
    {
        return true;
    }
}
