<?php

namespace Faibl\MailjetBundle\Serializer\Normalizer;

use Faibl\MailjetBundle\Model\MailjetContactCreateAndSubscribe;
use Faibl\MailjetBundle\Model\MailjetContactUnsubscribe;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MailjetContactNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = []): ?array
    {
        return match ($object::class) {
            MailjetContactCreateAndSubscribe::class => $this->normalizeCreateAndSubscribe($object, $format, $context),
            MailjetContactUnsubscribe::class => $this->normalizeUnsubscribe($object, $format, $context),
            default => null,
        };
    }

    private function normalizeCreateAndSubscribe(MailjetContactCreateAndSubscribe $object, $format = null, array $context = []): ?array
    {
        return [
            'Name' => $object->getName(),
            'Properties' => $object->getCustomProperties(),
            'Action' => $object->getAction(),
            'Email' => $object->getEmail(),
        ];
    }

    private function normalizeUnsubscribe(MailjetContactunsubscribe $object, $format = null, array $context = []): ?array
    {
        return [
            'IsUnsubscribed' => $object->isUnsubscribed(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return in_array($data::class, [MailjetContactCreateAndSubscribe::class, MailjetContactUnsubscribe::class]);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MailjetContactCreateAndSubscribe::class => true,
            MailjetContactUnsubscribe::class => true,
        ];
    }
}
