<?php

namespace Faibl\MailjetBundle\Model;

class MailjetBasicMail extends MailjetMail
{
    /** @var MailjetReceiver */
    private $sender;
    private $subject;
    private $textPart;
    private $htmlPart;

    public function getSender(): ?MailjetReceiver
    {
        return $this->sender;
    }

    public function setSender(MailjetReceiver $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTextPart(): ?string
    {
        return $this->textPart;
    }

    public function setTextPart(string $textPart): self
    {
        $this->textPart = $textPart;

        return $this;
    }

    public function getHtmlPart(): ?string
    {
        return $this->htmlPart;
    }

    public function setHtmlPart(string $htmlPart): self
    {
        $this->htmlPart = $htmlPart;

        return $this;
    }
}
