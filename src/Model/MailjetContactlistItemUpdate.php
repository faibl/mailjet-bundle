<?php

namespace Faibl\MailjetBundle\Model;

class MailjetContactlistItemUpdate extends MailjetContact
{
    public CONST ACTION_ADD_FORCE = 'addforce';
    public CONST ACTION_ADD_NO_FORCE = 'addnoforce';
    public CONST ACTION_UNSUBSCRIBE = 'unsub';
    public CONST ACTION_REMOVE = 'remove';

    private ?int $listId = null;
    private ?string $action = null;

    public function getListId(): ?int
    {
        return $this->listId;
    }

    public function setListId(?int $listId): static
    {
        $this->listId = $listId;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
    }
}
