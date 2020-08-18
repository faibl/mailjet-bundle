<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Exception\MailException;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Psr\Log\LoggerInterface;

class MailjetService implements MailjetServiceInterface
{
    private $apiKey;
    private $apiSecret;
    private $logger;
    private $mjClient;
    private $serializer;
    private $deliverDisabled;

    public function __construct(MailjetMailSerializer $serializer, LoggerInterface $logger, string $apiKey, string $apiSecret, string $apiVersion, bool $deliverDisabled = false)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->deliverDisabled = $deliverDisabled;
        $this->mjClient = new Client($apiKey, $apiSecret, true, ['version' => sprintf('v%s', $apiVersion)]);
    }

    public function send(MailjetMail $mail): bool
    {
        if (!$this->deliverDisabled) {
            $response = $this->mjClient->post(Resources::$Email, ['body' => $this->serializer->normalize($mail)]);
            $this->logErrors($response);
        }

        return true;
    }

    public function logErrors(Response $response): void
    {
        if ($response->getStatus() !== 200) {
            $error = sprintf(
                'Unexpected API-Response. Status: %s, Message. %s',
                $response->getStatus(),
                json_encode($response->getBody())
            );
            $this->logger->error($error);

            throw new MailException($error);
        }
    }
}
