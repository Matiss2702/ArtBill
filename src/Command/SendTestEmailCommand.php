<?php

namespace App\Command;

use App\Service\SendMailService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendTestEmailCommand extends Command
{
    private $sendMailService;

    public function __construct(SendMailService $sendMailService)
    {
        parent::__construct();

        $this->sendMailService = $sendMailService;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:send-test-email')
            ->setDescription('Sends a test email using the configured mailer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Sending test email...');

        // Vous pouvez personnaliser les adresses et le contenu de l'e-mail de test ici
        $from = 'artbill.team@outlook.fr';
        $to = 'matiss.haillouy@gmail.com';
        $subject = 'Subject of Test Email';
        $template = 'test_email_template';
        $context = ['variable' => 'value'];

        $this->sendMailService->send($from, $to, $subject, $template, $context);

        $output->writeln('Test email sent successfully.');

        return Command::SUCCESS;
    }
}
