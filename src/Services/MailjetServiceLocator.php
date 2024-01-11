<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Model\MailjetContactlistItemUpdate;
use Faibl\MailjetBundle\Model\MailjetMail;

class MailjetServiceLocator
{
    private array $services = [];

    public function addService(string $name, MailjetService $client): void
    {
        $this->services[$name] = $client;
    }

    public function getService(string $name): ?MailjetService
    {
        return $this->services[$name];
    }

    public function send(string $name, MailjetMail $mail, bool $sandboxMode = false): ?bool
    {
        $service = $this->getService($name);

        if (!$service) {
            throw new \Exception(sprintf('Unknown account %s. Please check your config.', $name));
        }

        return $service->send($mail, $sandboxMode);
    }

    public function sendBulk(string $name, array $mails, bool $sandboxMode = false): ?bool
    {
        $service = $this->getService($name);

        if (!$service) {
            throw new \Exception(sprintf('Unknown account %s. Please check your config.', $name));
        }

        return $service->sendBulk($mails, $sandboxMode);
    }

    public function contactlistItemUpdate(string $name, MailjetContactlistItemUpdate $contactlistItemUpdate): ?bool
    {
        $service = $this->getService($name);

        if (!$service) {
            throw new \Exception(sprintf('Unknown account %s. Please check your config.', $name));
        }

        return $service->contactlistItemUpdate($contactlistItemUpdate);
    }
}
