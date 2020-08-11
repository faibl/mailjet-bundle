<?php

namespace Faibl\MailjetBundle\Serializer\Normalizer;

use Faibl\MailjetBundle\Model\MailjetBasicMail;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Model\MailjetReceiver;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use App\Util\ArrayUtil;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MailjetMailNormalizer implements NormalizerInterface
{
    const SENDER_NAME = 'ZEIT ONLINE Studienorientierung';
    const SENDER_MAIL = 'norelpy@suma.mailing.zeit.de';

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

    private function normalizeBasicMailContent(MailjetBasicMail $mail, array $context = []): array
    {
        return array_merge(
            $this->normalizeMessageBase($mail),
            [
                'From' => [
                    'Email' => self::SENDER_MAIL,
                    'Name' => self::SENDER_NAME,
                ],
                'Subject' => $mail->getSubject(),
                'TextPart' => $mail->getTextPart(),
                'HtmlPart' => $mail->getHtmlPart(),
                'Attachments' => [[
                    'ContentType' => $mail->getAttachment()->getContentType(),
                    'Filename' => $mail->getAttachment()->getFilename(),
                    'Base64Content' => $mail->getAttachment()->getContent(),
                ]],
            ]
        );
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
                    "Email" => $context['receiverErrors'],
                ],
            ]
        );
    }

    private function normalizeMessageBase(MailjetMail $mail): array
    {
        return [
            'To' => $this->normalizeReceiver($mail->getReceiver()),
            'Cc' => $this->normalizeReceiver($mail->getReceiverCC()),
            'Bc' => $this->normalizeReceiver($mail->getReceiverBC()),
        ];
    }

    private function normalizeReceiver(array $receiver): array
    {
        return array_map(function (MailjetReceiver $receiver) {
            return [
                'Email' => $receiver->getEmail(),
                'Name' => $receiver->getName(),
            ];
        }, $receiver);
    }
}
