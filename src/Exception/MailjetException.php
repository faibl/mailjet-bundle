<?php

namespace Faibl\MailjetBundle\Exception;

class MailjetException extends \Exception
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
