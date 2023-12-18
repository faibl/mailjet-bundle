<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Exception\MailjetException;
use Faibl\MailjetBundle\Model\MailjetContactToList;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Psr\Log\LoggerInterface;

class MailjetService
{
    public function __construct(
        private Client $client,
        private MailjetMailSerializer $serializer,
        private LoggerInterface $logger
    ) {
    }

    public function send(MailjetMail $mail, bool $sandboxMode = false): ?bool
    {
        $message = $this->serializer->normalize($mail);

        $body = [
            'Messages' => [$message],
            'SandboxMode' => $sandboxMode,
        ];

        $response = $this->client->post(Resources::$Email, ['body' => $body], ['version' => 'v3.1']);

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

        $response = $this->client->post(Resources::$Email, ['body' => $body], ['version' => 'v3.1']);

        $this->logErrors($response, $body);

        return $response->success();
    }

    public function createContactAndAddToList(MailjetContactToList $contactToList): ?bool
    {
        $body = $this->serializer->normalize($contactToList);

        $response = $this->client->post(Resources::$ContactslistManagecontact, ['id' => $contactToList->getListId(), 'body' => $body], ['version' => 'v3']);

        $this->logErrors($response, array_merge(['id' => $contactToList->getListId()], $body));

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
