<?php

namespace Faibl\MailjetBundle\Model;

class MailjetContact
{
    private ?string $email = null;
    private ?string $name = null;
    private array $customProperties = [];

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCustomProperties(): array
    {
        return $this->customProperties;
    }

    public function setCustomProperties(array $customProperties): void
    {
        $this->customProperties = $customProperties;
    }

    public function addCustomProperty(string $key, mixed $value): void
    {
        $this->customProperties[$key] = $value;
    }
}
