<?php

namespace Faibl\MailjetBundle\Model;

class MailjetMail
{
    private $receivers = [];
    private $receiversCc = [];
    private $receiversBcc = [];
    private $attachments = [];
    private $sandboxMode = false;

    public function isReceiver(MailjetAddress $receiver): bool
    {
        foreach ($this->getReceivers() as $existingReceiver) {
            /** @var MailjetAddress $existingReceiver */
            if ($existingReceiver->getEmail() === $receiver->getEmail()) {
                return true;
            }
        }

        return false;
    }

    public function addReceiver(MailjetAddress $receiver): self
    {
        $this->receivers[] = $receiver;

        return $this;
    }

    public function addReceivers(MailjetAddressCollection $receiverCollection): self
    {
        foreach ($receiverCollection->getAddresses() as $receiver) {
            $this->addReceiver($receiver);
        }

        return $this;
    }

    public function getReceivers(): array
    {
        return $this->receivers;
    }

    public function addReceiverCc(MailjetAddress $receiver): self
    {
        if (!$this->isReceiver($receiver)) {
            $this->receiversCc[] = $receiver;
        }

        return $this;
    }

    public function addReceiversCc(MailjetAddressCollection $receiverCollection): self
    {
        foreach ($receiverCollection->getAddresses() as $receiver) {
            $this->addReceiverCc($receiver);
        }

        return $this;
    }

    public function getReceiversCc(): array
    {
        return $this->receiversCc;
    }

    public function addReceiverBcc(MailjetAddress $receiver): self
    {
        if (!$this->isReceiver($receiver)) {
            $this->receiversBcc[] = $receiver;
        }

        return $this;
    }

    public function addReceiversBcc(MailjetAddressCollection $receiverCollection): self
    {
        foreach ($receiverCollection->getAddresses() as $receiver) {
            $this->addReceiverBcc($receiver);
        }

        return $this;
    }

    public function getReceiversBcc(): array
    {
        return $this->receiversBcc;
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
