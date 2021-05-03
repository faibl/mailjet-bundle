<?php

namespace Faibl\MailjetBundle\Model;

class MailjetMail
{
    private $receiver = [];
    private $receiverCc = [];
    private $receiverBcc = [];
    private $attachments = [];
    private $sandboxMode = false;

    public function addReceiver(MailjetAddress $receiver): self
    {
        $this->receiver[] = $receiver;

        return $this;
    }

    public function addReceivers(MailjetAddressCollection $receiverCollection): self
    {
        foreach ($receiverCollection->getAddresses() as $receiver) {
            $this->addReceiver($receiver);
        }

        return $this;
    }

    public function getReceiver(): array
    {
        return $this->receiver;
    }

    public function addReceiverCc(MailjetAddress $receiver): self
    {
        $this->receiverCc[] = $receiver;

        return $this;
    }

    public function addReceiversCc(MailjetAddressCollection $receiverCollection): self
    {
        foreach ($receiverCollection->getAddresses() as $receiver) {
            $this->addReceiverCc($receiver);
        }

        return $this;
    }

    public function getReceiverCc(): array
    {
        return $this->receiverCc;
    }

    public function addReceiverBcc(MailjetAddress $receiver): self
    {
        $this->receiverBcc[] = $receiver;

        return $this;
    }

    public function addReceiversBcc(MailjetAddressCollection $receiverCollection): self
    {
        foreach ($receiverCollection->getAddresses() as $receiver) {
            $this->addReceiverBcc($receiver);
        }

        return $this;
    }

    public function getReceiverBcc(): array
    {
        return $this->receiverBcc;
    }

    public function addAttachment(MailjetAttachment $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @deprecated 
     */
    public function setAttachment(MailjetAttachment $attachment): self
    {
        return $this->addAttachment($attachment);
    }

    public function setAttachments(array $attachments): self
    {
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }

        return $this;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function isSandboxMode(): bool
    {
        return $this->sandboxMode;
    }

    public function setSandboxMode(bool $sandboxMode): self
    {
        $this->sandboxMode = $sandboxMode;

        return $this;
    }
}
