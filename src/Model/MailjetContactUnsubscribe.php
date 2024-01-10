<?php

namespace Faibl\MailjetBundle\Model;

class MailjetContactUnsubscribe extends MailjetContact
{
    private ?int $listId = null;
    private bool $isUnsubscribed = true;

    public function getListId(): ?int
    {
        return $this->listId;
    }

    public function setListId(?int $listId): static
    {
        $this->listId = $listId;

        return $this;
    }

    public function isUnsubscribed(): bool
    {
        return $this->isUnsubscribed;
    }

    public function setIsUnsubscribed(bool $isUnsubscribed): static
    {
        $this->isUnsubscribed = $isUnsubscribed;

        return $this;
    }
}
