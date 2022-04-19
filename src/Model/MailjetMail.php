<?php

namespace Faibl\MailjetBundle\Model;

class MailjetMail
{
    private array $receivers = [];
    private array $receiversCc = [];
    private array $receiversBcc = [];
    private array $attachments = [];
    private bool $sandboxMode = false;

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

    public function addReceiver(MailjetAddress $receiver): static
    {
        $this->receivers[] = $receiver;

        return $this;
    }

    public function addReceivers(MailjetAddressCollection $receiverCollection): static
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

    public function addReceiverCc(MailjetAddress $receiver): static
    {
        if (!$this->isReceiver($receiver)) {
            $this->receiversCc[] = $receiver;
        }

        return $this;
    }

    public function addReceiversCc(MailjetAddressCollection $receiverCollection): static
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

    public function addReceiverBcc(MailjetAddress $receiver): static
    {
        if (!$this->isReceiver($receiver)) {
            $this->receiversBcc[] = $receiver;
        }

        return $this;
    }

    public function addReceiversBcc(MailjetAddressCollection $receiverCollection): static
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

    public function addAttachment(MailjetAttachment $attachment): static
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @deprecated
     */
    public function setAttachment(MailjetAttachment $attachment): static
    {
        return $this->addAttachment($attachment);
    }

    public function setAttachments(array $attachments): static
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

    public function setSandboxMode(bool $sandboxMode): static
    {
        $this->sandboxMode = $sandboxMode;

        return $this;
    }
}
