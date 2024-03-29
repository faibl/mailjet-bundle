<?php

namespace Faibl\MailjetBundle\Model;

class MailjetTextMail extends MailjetMail
{
    private ?MailjetAddress $sender = null;
    private ?string $subject = null;
    private ?string $textPart = null;
    private ?string $htmlPart = null;

    public function getSender(): ?MailjetAddress
    {
        return $this->sender;
    }

    public function setSender(MailjetAddress $sender): self
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
