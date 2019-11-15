<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mailer;

use Knp\Snappy\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;
use Twig\Environment;

class GrrMailer
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var Pdf
     */
    private $pdf;

    public function __construct(MailerInterface $mailer, Environment $twig, Pdf $pdf)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->pdf = $pdf;
    }

    public function sendWelcome(string $email): TemplatedEmail
    {
        $message = EmailFactory::createNewTemplated();
        $message
            ->to($email)
            ->from('jf@marche.be')
            ->subject('test')
            ->htmlTemplate('email/welcome.html.twig')
            ->context(
                [
                    'zeze' => 'lolo',
                ]
            );
        $this->send($message);

        return $message;
    }

    public function sendTest(): TemplatedEmail
    {
        $html = $this->twig->render(
            'pdf/test.html.twig',
            [

            ]
        );
        $pdf = $this->pdf->getOutputFromHtml($html);

        $message = (EmailFactory::createNewTemplated())
            ->to(new NamedAddress('jf@marche.be', 'zeze'))
            ->subject('Your weekly report on the Space Bar!')
            ->htmlTemplate('email/welcome2.html.twig')
            ->context(
                [
                    'zeze' => 'jf',
                ]
            )
            ->attach($pdf, sprintf('weekly-report-%s.pdf', date('Y-m-d')));

        $this->send($message);

        return $message;

    }

    public function send(Email $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            var_dump($e->getMessage());
        }
    }
}
