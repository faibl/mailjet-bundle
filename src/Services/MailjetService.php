<?php

namespace Faibl\MailjetBundle\Services;

use Faibl\MailjetBundle\Exception\MailException;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Serializer\Serializer\MailjetMailSerializer;
use Mailjet\Client;
use Mailjet\Resources;
use Psr\Log\LoggerInterface;

class MailjetService implements MailjetServiceInterface
{
    private $apiKey;
    private $apiSecret;
    private $logger;
    private $mjClient;
    private $serializer;
    private $receiverErrors;

    public function __construct(MailjetMailSerializer $serializer, LoggerInterface $loggerRollbar, string $apiKey, string $apiSecret, string $receiverErrors)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->logger = $loggerRollbar;
        $this->serializer = $serializer;
        $this->receiverErrors = $receiverErrors;
        $this->mjClient = new Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
    }

    public function send(MailjetMail $mail): bool
    {
        $body = $this->serializer->normalize($mail, null, ['receiverErrors' => $this->receiverErrors]);

        $response = $this->mjClient->post(Resources::$Email, ['body' => $body]);

        if ($response->getStatus() !== 200) {
            $error = sprintf(
                'Unexpected API-Response. Status: %s, Message. %s',
                $response->getStatus(),
                json_encode($response->getBody())
            );
            //@todo: use newer version of monolog and proper rollbar handler..
            $this->logger->error($error);
            
            throw new MailException($error);
        }

        return true;
    }
}
