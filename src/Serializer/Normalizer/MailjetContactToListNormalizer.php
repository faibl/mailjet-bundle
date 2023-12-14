<?php

namespace Faibl\MailjetBundle\Serializer\Normalizer;

use Faibl\MailjetBundle\Model\MailjetContactToList;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MailjetContactToListNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = []): ?array
    {
        if (!$object instanceof MailjetContactToList) {
            return null;
        }

        return [
            'Name' => $object->getName(),
            'Properties' => $object->getCustomProperties(),
            'Action' => $object->getAction(),
            'Email' => $object->getEmail(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof MailjetContactToList;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MailjetContactToList::class => true
        ];
    }
}
