<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Model\MailjetMail;

interface MailjetServiceInterface
{
    public function send(MailjetMail $mail): ?bool;
}
