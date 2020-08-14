<?php

namespace Faibl\MailjetBundle\Serializer\Normalizer;

use Faibl\MailjetBundle\Model\MailjetBasicMail;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Model\MailjetReceiver;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Faibl\MailjetBundle\Util\ArrayUtil;
use Mailjet\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MailjetMailNormalizer implements NormalizerInterface
{
    private $receiverErrors;
    private $deliveryAddress;
    private $deliveryDisabled;

    public function __construct(string $receiverErrors, string $deliveryAddress = null, bool $deliveryDisabled = false)
    {
        $this->receiverErrors = $receiverErrors;
        $this->deliveryAddress = $deliveryAddress;
        $this->deliveryDisabled = $deliveryDisabled;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        if (!$object instanceof MailjetMail) {
            return null;
        }

        $messages = array_merge(
            $this->normalizeMessageBase($object),
            $this->normalizeMessageContent($object, $context)
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
            case ($mail instanceof MailjetBasicMail):
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

    private function normalizeBasicMail(MailjetBasicMail $mail, array $context = []): array
    {
        $mail =  array_merge(
            $this->normalizeMessageBase($mail),
            $this->normalizeBasicMailContent($mail)
        );

        return ArrayUtil::filterEmptyRecursive($mail);
    }

    private function normalizeBasicMailContent(MailjetBasicMail $mail, array $context = []): array
    {
        return [
            'From' => $mail->getSender() ? $this->normalizeReceiver($mail->getSender()) : null,
            'Subject' => $mail->getSubject(),
            'TextPart' => $mail->getTextPart() ?? null,
            'HtmlPart' => $mail->getHtmlPart() ?? null,
            'Attachments' => $this->normalizeAttachment($mail),
        ];
    }

    private function normalizeAttachment(MailjetBasicMail $mail, array $context = []): array
    {
        return $mail->getAttachment() ? [[
            'ContentType' => $mail->getAttachment()->getContentType(),
            'Filename' => $mail->getAttachment()->getFilename(),
            'Base64Content' => $mail->getAttachment()->getContent(),
        ]] : [];
    }

    private function normalizeTemplateMailContent(MailjetTemplateMail $mail, array $context = []): array
    {
        return array_merge(
            $this->normalizeMessageBase($mail),
            [
                'TemplateLanguage' => true,
                'TemplateID' => $mail->getTemplateId(),
                'Variables' => $mail->getVariables(),
                "TemplateErrorReporting" => [
                    "Email" => $this->receiverErrors,
                ],
            ]
        );
    }

    private function normalizeMessageBase(MailjetMail $mail): array
    {
        if (empty($this->deliveryAddress)) {
            return [
                'To' => $this->normalizeReceivers($mail->getReceiver()),
                'Cc' => $this->normalizeReceivers($mail->getReceiverCC()),
                'Bc' => $this->normalizeReceivers($mail->getReceiverBC()),
            ];
        }

        return [
            'To' => [$this->normalizeReceiver(new MailjetReceiver($this->deliveryAddress))],
        ];
    }

    private function normalizeReceivers(array $receivers): array
    {
        return array_map(function (MailjetReceiver $receiver) {
            return $this->normalizeReceiver($receiver);
        }, $receivers);
    }

    private function normalizeReceiver(MailjetReceiver $receiver): array
    {
        return [
            'Email' => $receiver->getEmail(),
            'Name' => $receiver->getName(),
        ];
    }
}
