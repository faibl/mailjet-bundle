<?php

namespace Faibl\MailjetBundle\Serializer\Serializer;

use Faibl\MailjetBundle\Serializer\Normalizer\MailjetContactNormalizer;
use Faibl\MailjetBundle\Serializer\Normalizer\MailjetContactUnsubscribeNormalizer;
use Faibl\MailjetBundle\Serializer\Normalizer\MailjetMailNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class MailjetMailSerializer extends Serializer
{
    public function __construct(
        MailjetMailNormalizer $mailjetMailNormalizer,
        MailjetContactNormalizer $mailjetContactToListNormalizer,
        JsonEncoder $jsonEncoder
    ) {
        parent::__construct(
            [$mailjetMailNormalizer, $mailjetContactToListNormalizer],
            [$jsonEncoder]
        );
    }
}
