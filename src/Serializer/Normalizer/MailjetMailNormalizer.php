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
    private $receiverErrors;
    private $deliveryAddresses;
    private $deliveryDisabled;

    public function __construct(string $receiverErrors = null, string $deliveryAddresses = null, bool $deliveryDisabled = false)
    {
        $this->receiverErrors = $receiverErrors;
        // unfortunately we cannot pass array as env-vars
        // to solve this, multiple email-addresses can be provided in comma-separated string
        $this->deliveryAddresses = ArrayUtil::stringToArray($deliveryAddresses);
        $this->deliveryDisabled = $deliveryDisabled;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        if (!$object instanceof MailjetMail) {
            return null;
        }

        $messages = array_merge(
            $this->normalizeMessageBase($object),
            $this->normalizeMessageContent($object, $context),
            $this->normalizeSandboxMode($object)
        );

        return [
            'Messages' => [
                ArrayUtil::filterEmptyRecursive($messages)
            ]
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof MailjetMail;
    }

    private function normalizeMessageContent(MailjetMail $mail, $context): array
    {
        switch (true) {
            case ($mail instanceof MailjetTextMail):
                $messages = $this->normalizeBasicMailContent($mail, $context);
                break;
            case ($mail instanceof MailjetTemplateMail):
                $messages = $this->normalizeTemplateMailContent($mail, $context);
                break;
            default:
                throw new \InvalidArgumentException('Mail-Object of type %s cannot be normalized', get_class($mail));
        }

        return $messages;
    }

    private function normalizeBasicMailContent(MailjetTextMail $mail, array $context = []): array
    {
        return [
            'From' => $mail->getSender() ? $this->normalizeReceiver($mail->getSender()) : null,
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
                'To' => $this->normalizeReceivers($mail->getReceivers()),
                'Cc' => $this->normalizeReceivers($mail->getReceiversCc()),
                'Bcc' => $this->normalizeReceivers($mail->getReceiversBcc()),
            ];
        }

        return [
            // if delivery_addresses is set, override all other receivers
            'To' => $this->normalizeAddressCollection(new MailjetAddressCollection($this->deliveryAddresses)),
        ];
    }

    private function normalizeSandboxMode(MailjetMail $mail): array
    {
        return $mail->isSandboxMode() ? ['SandboxMode' => true] : [];
    }

    private function normalizeAddressCollection(MailjetAddressCollection $collection): array
    {
        return array_map(function (MailjetAddress $receiver) {
            return $this->normalizeReceiver($receiver);
        }, $collection->getAddresses());
    }

    private function normalizeReceivers(array $receivers): array
    {
        return array_map(function (MailjetAddress $receiver) {
            return $this->normalizeReceiver($receiver);
        }, $receivers);
    }

    private function normalizeReceiver(MailjetAddress $receiver): array
    {
        return [
            'Email' => $receiver->getEmail(),
            'Name' => $receiver->getName(),
        ];
    }
}
