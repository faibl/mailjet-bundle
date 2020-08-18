<?php

namespace Faibl\MailjetBundle\Command;

use Faibl\MailjetBundle\Exception\MailjetException;
use Faibl\MailjetBundle\Model\MailjetTextMail;
use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Model\MailjetAddress;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Services\MailjetServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'fbl:mailjet:test';
    /** @var SymfonyStyle */
    private $io;
    private $mailjetService;

    public function __construct(MailjetService $mailjetService)
    {
        $this->mailjetService = $mailjetService;
        parent::__construct($this::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Sends testmail through MailJet')
            ->addArgument('type', InputArgument::OPTIONAL, 'Type of mail. Options are text or template', 'text')
            ->addOption('receiver', null, InputOption::VALUE_REQUIRED, 'Email address of receiver', null)
            ->addOption('sender', null, InputOption::VALUE_REQUIRED, 'Email address of sender', null)
            ->addOption('template-id', null, InputOption::VALUE_REQUIRED, 'MailJet template-id. Required for type=template', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $type = $input->getArgument('type');
        $receiver = $input->getOption('receiver');
        $sender = $input->getOption('sender');
        $templateId = $input->getOption('template-id');

        if (empty($receiver)) {
            throw new \InvalidArgumentException(sprintf('The required option %s is not set', 'receiver'));
        }

        if ($type === 'text' && empty($sender)) {
            throw new \InvalidArgumentException(sprintf('The required option %s is not set', 'sender'));
        }

        if ($type === 'template' && empty($templateId)) {
            throw new \InvalidArgumentException(sprintf('The required option %s is not set', 'template-id'));
        }

        $this->io->section(sprintf('Send %s-mail to %s', $type, $receiver));

        switch ($type) {
            case 'text':
                $success = $this->sendTestTextMail($receiver, $sender);
                break;
            case 'template':
                $success = $this->sendTemplateMail($receiver, (int) $templateId);
                break;
            default:
                throw new MailjetException(sprintf('Unknown argument type provided. Options are text or template, %s provided', $type));
        }

        if ($success === true) {
            $this->io->success('E-Mail Successfully sent.');
        } elseif ($success === false) {
            $this->io->error('E-Mail could not be sent. Check your logs for more information.');
        } else {
            $this->io->text('No E-mail is sent. Dis you disable delivery?');
        }

        return 1;
    }

    private function sendTestTextMail(string $receiver, string $sender): ?bool
    {
        $mail = (new MailjetTextMail())
            ->setSender((new MailjetAddress($sender)))
            ->addReceiver((new MailjetAddress($receiver)))
            ->setSubject('Testmail send by faibl-mailjet-bundle')
            ->setTextPart('Nothing to say...');

        return $this->mailjetService->send($mail);
    }

    private function sendTemplateMail(string $receiver, int $templateId): ?bool
    {
        $mail = (new MailjetTemplateMail($templateId))
            ->addReceiver((new MailjetAddress($receiver)));

        return $this->mailjetService->send($mail);
    }
}
