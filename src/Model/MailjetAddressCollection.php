<?php

namespace Faibl\MailjetBundle\Model;

class MailjetAddressCollection
{
    private array $addresses = [];

    public function __construct(array $addresses = [])
    {
        foreach ($addresses as $address) {
            $this->addresses[] = new MailjetAddress($address);
        }
    }

    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function addAddress(MailjetAddress $address): self
    {
        $this->addresses[] = $address;

        return $this;
    }
}
