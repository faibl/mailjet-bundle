<?php

namespace Faibl\MailjetBundle\Model;

class MailjetMail
{
    private $receiver = [];
    private $receiverCC = [];
    private $receiverBC = [];
    private $attachment;

    public function addReceiver(MailjetAddress $receiver): self
    {
        $this->receiver[] = $receiver;

        return $this;
    }

    public function getReceiver(): array
    {
        return $this->receiver;
    }

    public function addReceiverCC(MailjetAddress $receiver): self
    {
        $this->receiverCC[] = $receiver;

        return $this;
    }

    public function getReceiverCC(): array
    {
        return $this->receiverCC;
    }

    public function addReceiverBC(MailjetAddress $receiver): self
    {
        $this->receiverBC[] = $receiver;

        return $this;
    }

    public function getReceiverBC(): array
    {
        return $this->receiverBC;
    }

    public function getAttachment(): ?MailjetAttachment
    {
        return $this->attachment;
    }

    public function setAttachment(MailjetAttachment $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }
}
