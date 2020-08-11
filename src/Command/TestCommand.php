<?php

namespace Faibl\MailjetBundle\Command;

use Faibl\MailjetBundle\Model\MailjetMail;
use Faibl\MailjetBundle\Model\MailjetTemplateMail;
use Faibl\MailjetBundle\Services\MailjetService;
use Faibl\MailjetBundle\Services\MailjetServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'faibl:mailjet:test';
    private $mailjetService;

    public function __construct(MailjetService $mailjetService)
    {
        $this->mailjetService = $mailjetService;
        parent::__construct($this::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $mail = new MailjetTemplateMail(123, 'hannes@faibl.com', 'Hannes', ['firstname' => 'Hannes', 'riasec' => 'cis']);
        $this->mailjetService->send($mail);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
