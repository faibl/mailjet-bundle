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
    private $logger;
    private $client;
    private $serializer;

    public function __construct(Client $client, MailjetMailSerializer $serializer, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function send(MailjetMail $mail): ?bool
    {
        $massage = $this->serializer->normalize($mail);

        $content = [
            'body' => [
                'Messages' => [$massage]
            ]
        ];

        $response = $this->client->post(Resources::$Email, $content);

        $this->logErrors($response, $content);

        return $response->success();
    }

    public function sendBulk(array $mails): ?bool
    {
        $massages = $this->serializer->normalize($mails);

        $content = [
            'body' => [
                'Messages' => $massages
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
                'Unexpected API-Response. Status: %s, Message: %s, Mail: %s',
                $response->getStatus(),
                $response->getReasonPhrase(),
                print_r($body, true)
            );
            $this->logger->error($error);

            throw new MailjetException($error);
        }
    }
}
