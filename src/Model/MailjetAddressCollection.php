<?php

namespace Faibl\MailjetBundle\Model;

class MailjetAddressCollection
{
    private $addresses = [];

    public function __construct(array $addressCollection = [])
    {
        foreach ($addressCollection as $address) {
            $this->addresses[] = new MailjetAddress($address[0], $address[1]);
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
