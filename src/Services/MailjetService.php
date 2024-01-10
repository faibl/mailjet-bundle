<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Exception\MailjetException;
use Faibl\MailjetBundle\Model\MailjetContactCreateAndSubscribe;
use Faibl\MailjetBundle\Model\MailjetContactUnsubscribe;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Psr\Log\LoggerInterface;

class MailjetService
{
    public function __construct(
        private readonly Client $client,
        private readonly MailjetMailSerializer $serializer,
        private readonly LoggerInterface $logger,
        private readonly bool $deliveryEnabled
    ) {
    }

    public function send(MailjetMail $mail, bool $sandboxMode = false): ?bool
    {
        $message = $this->serializer->normalize($mail);

        $body = [
            'Messages' => [$message],
            'SandboxMode' => $sandboxMode,
        ];

        $response = $this->client->post(Resources::$Email, ['body' => $body], [
            'version' => 'v3.1',
            'call' => $this->deliveryEnabled,
        ]);

        $this->logErrors($response, $body);

        return $response->success();
    }

    public function sendBulk(array $mails, bool $sandboxMode = false): ?bool
    {
        $messages = $this->serializer->normalize($mails);

        $body = [
            'Messages' => $messages,
            'SandboxMode' => $sandboxMode,
        ];

        $response = $this->client->post(Resources::$Email, ['body' => $body], [
            'version' => 'v3.1',
            'call' => $this->deliveryEnabled,
        ]);

        $this->logErrors($response, $body);

        return $response->success();
    }

    public function contactCreateAndSubscribe(MailjetContactCreateAndSubscribe $contactCreateAndSubscribe): ?bool
    {
        $body = $this->serializer->normalize($contactCreateAndSubscribe);

        $response = $this->client->post(Resources::$ContactslistManagecontact, ['id' => $contactCreateAndSubscribe->getListId(), 'body' => $body], [
            'version' => 'v3',
            'call' => $this->deliveryEnabled,
        ]);

        $this->logErrors($response, array_merge(['id' => $contactCreateAndSubscribe->getListId()], $body));

        return $response->success();
    }

    public function contactUnsubscribe(MailjetContactUnsubscribe $contactUnsubscribe): ?bool
    {
        $body = $this->serializer->normalize($contactUnsubscribe);

        $response = $this->client->post(Resources::$Listrecipient, ['id' => $contactUnsubscribe->getListId(), 'body' => $body], [
            'version' => 'v3',
            'call' => $this->deliveryEnabled,
        ]);

        $this->logErrors($response, array_merge(['id' => $contactUnsubscribe->getListId()], $body));

        return $response->success();
    }

    private function logErrors(Response $response, array $body): void
    {
        if ($response->success() === false) {
            $error = sprintf(
                'Unexpected API-Response. Status: %s, Message: %s, Request: %s, Response: %s',
                $response->getStatus(),
                $response->getReasonPhrase(),
                print_r($body, true),
                print_r($response->getBody(), true)
            );
            $this->logger->error($error);

            throw new MailjetException($error);
        }
    }
}
