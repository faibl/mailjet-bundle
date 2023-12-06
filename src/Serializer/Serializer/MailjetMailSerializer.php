<?php

namespace Faibl\MailjetBundle\Serializer\Serializer;

use Faibl\MailjetBundle\Serializer\Normalizer\MailjetMailNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class MailjetMailSerializer extends Serializer
{
    public function __construct(
        MailjetMailNormalizer $mailjetMailNormalizer,
        JsonEncoder $jsonEncoder
    ) {
        parent::__construct(
            [$mailjetMailNormalizer],
            [$jsonEncoder]
        );
    }
}
