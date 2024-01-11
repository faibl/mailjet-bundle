<?php

namespace Faibl\MailjetBundle\Serializer\Normalizer;

use Faibl\MailjetBundle\Model\MailjetContactlistItemUpdate;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MailjetContactlistItemNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = []): ?array
    {
        return match ($object::class) {
            MailjetContactlistItemUpdate::class => $this->normalizeItemUpdate($object, $format, $context),
            default => null,
        };
    }

    private function normalizeItemUpdate(MailjetContactlistItemUpdate $object, $format = null, array $context = []): ?array
    {
        return [
            'Name' => $object->getName(),
            'Properties' => $object->getCustomProperties(),
            'Action' => $object->getAction(),
            'Email' => $object->getEmail(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return in_array($data::class, [MailjetContactlistItemUpdate::class]);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MailjetContactlistItemUpdate::class => true,
        ];
    }
}
