<?php

namespace App\Command;

use App\Mailer\EmailFactory;
use App\Mailer\GrrMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'grr:send-email';
    /**
     * @var EmailFactory
     */
    private $emailFactory;
    /**
     * @var GrrMailer
     */
    private $grrMailer;

    public function __construct(MailerInterface $grrMailer)
    {
        parent::__construct();
        $this->grrMailer = $grrMailer;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email to');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $message = EmailFactory::createNewTemplated();
        $message
            ->to($email)
            ->from('jf@marche.be')
            ->subject('test')
            ->htmlTemplate('email/welcome.html.twig')
        ->context([
            'zeze' => 'lolo',
        ]);

        try {
            $this->grrMailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $io->error($e->getMessage());
        }

        return 0;
    }
}
