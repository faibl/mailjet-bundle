<?php

namespace Faibl\MailjetBundle\Serializer\Normalizer;

use Faibl\MailjetBundle\Model\MailjetAddressCollection;
use Faibl\MailjetBundle\Model\MailjetAttachment;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Util\ArrayUtil;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MailjetMailNormalizer implements NormalizerInterface
{
    private ?string $receiverErrors;
    private array $deliveryAddresses;

    public function __construct(string $receiverErrors = null, string $deliveryAddresses = null)
    {
        $this->receiverErrors = $receiverErrors;
        // unfortunately we cannot pass array as env-vars
        // to solve this, multiple email-addresses can be provided in comma-separated string
        $this->deliveryAddresses = ArrayUtil::stringToArray($deliveryAddresses);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        if (!$object instanceof MailjetMail) {
            return null;
        }

        $messages = array_merge(
            $this->normalizeMessageBase($object),
            $this->normalizeMessageContent($object, $context)
        );

        return ArrayUtil::filterEmptyRecursive($messages);
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof MailjetMail;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MailjetMail::class => true
        ];
    }

    private function normalizeMessageContent(MailjetMail $mail, $context): array
    {
        return match (true) {
            $mail instanceof MailjetTextMail => $this->normalizeBasicMailContent($mail, $context),
            $mail instanceof MailjetTemplateMail => $this->normalizeTemplateMailContent($mail, $context),
        };
    }

    private function normalizeBasicMailContent(MailjetTextMail $mail, array $context = []): array
    {
        return [
            'From' => $mail->getSender() ? $this->normalizeAddress($mail->getSender()) : null,
            'Subject' => $mail->getSubject(),
            'TextPart' => $mail->getTextPart() ?? null,
            'HtmlPart' => $mail->getHtmlPart() ?? null,
            'Attachments' => $this->normalizeAttachments($mail),
        ];
    }

    private function normalizeAttachments(MailjetMail $mail, array $context = []): array
    {
        return array_map(function (MailjetAttachment $attachment) {
            return $this->normalizeAttachment($attachment);
        }, $mail->getAttachments());
    }

    private function normalizeAttachment(MailjetAttachment $attachment): array
    {
        return [
            'ContentType' => $attachment->getContentType(),
            'Filename' => $attachment->getFilename(),
            'Base64Content' => $attachment->getContent(),
        ];
    }

    private function normalizeTemplateMailContent(MailjetTemplateMail $mail, array $context = []): array
    {
        return [
            'TemplateLanguage' => true,
            'TemplateID' => $mail->getTemplateId(),
            'Variables' => $mail->getVariables(),
            'Attachments' => $this->normalizeAttachments($mail),
            "TemplateErrorReporting" => [
                "Email" => $this->receiverErrors,
            ],
        ];
    }

    private function normalizeMessageBase(MailjetMail $mail): array
    {
        if (empty($this->deliveryAddresses)) {
            return [
                'To' => $this->normalizeAddresses($mail->getReceivers()),
                'Cc' => $this->normalizeAddresses($mail->getReceiversCc()),
                'Bcc' => $this->normalizeAddresses($mail->getReceiversBcc()),
            ];
        }

        return [
            // if delivery_addresses is set, override all other receivers
            'To' => $this->normalizeAddressCollection(new MailjetAddressCollection($this->deliveryAddresses)),
        ];
    }

    private function normalizeAddressCollection(MailjetAddressCollection $collection): array
    {
        return array_map(function (MailjetAddress $receiver) {
            return $this->normalizeAddress($receiver);
        }, $collection->getAddresses());
    }

    private function normalizeAddresses(array $receivers): array
    {
        return array_map(function (MailjetAddress $receiver) {
            return $this->normalizeAddress($receiver);
        }, $receivers);
    }

    private function normalizeAddress(MailjetAddress $receiver): array
    {
        return [
            'Email' => $receiver->getEmail(),
            'Name' => $receiver->getName(),
        ];
    }
}
