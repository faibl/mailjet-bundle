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

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCustomProperties(): array
    {
        return $this->customProperties;
    }

    public function setCustomProperties(array $customProperties): static
    {
        $this->customProperties = $customProperties;

        return $this;
    }

    public function addCustomProperty(string $key, mixed $value): static
    {
        $this->customProperties[$key] = $value;

        return $this;
    }
}
