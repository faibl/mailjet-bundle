<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Exception\MailjetException;
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

    public function send(MailjetMail $mail): ?bool
    {
        $messages = $this->serializer->normalize($mail);

        $content = [
            'body' => [
                'Messages' => [$messages]
            ]
        ];

        $response = $this->client->post(Resources::$Email, $content);

        $this->logErrors($response, $content);

        return $response->success();
    }

    public function sendBulk(array $mails): ?bool
    {
        $messages = $this->serializer->normalize($mails);

        $content = [
            'body' => [
                'Messages' => $messages
            ]
        ];

        $response = $this->client->post(Resources::$Email, $content);

        $this->logErrors($response, $content);

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
