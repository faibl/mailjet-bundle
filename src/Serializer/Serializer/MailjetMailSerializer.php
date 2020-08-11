<?php

namespace Faibl\MailjetBundle\Serializer\Serializer;

use Faibl\MailjetBundle\Serializer\Normalizer\MailjetMailNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class MailjetMailSerializer extends Serializer implements SerializerInterface
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
