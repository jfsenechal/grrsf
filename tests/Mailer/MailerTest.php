<?php

namespace App\Tests\Mailer;

use App\Mailer\GrrMailer;
use Knp\Snappy\Pdf;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\NamedAddress;
use Twig\Environment;
use Twig\Loader\LoaderInterface;


class MailerTest extends TestCase
{
    public function testSomething()
    {
        $mailerInterface = $this->getMockBuilder(MailerInterface::class)->getMock();
        $mailerInterface
            ->expects($this->once())
            ->method('send');

        $pdf = $this->getMockBuilder(Pdf::class)->getMock();
        $loader = $this->getMockBuilder(LoaderInterface::class)->getMock();
        $twig = $this->getMockBuilder(Environment::class);
        $twig->setConstructorArgs([$loader]);

        $grrMailer = new GrrMailer($mailerInterface, $twig->getMock(), $pdf);
        $email = $grrMailer->sendTest();

        $this->assertSame('Your weekly report on the Space Bar!', $email->getSubject());
        $this->assertCount(1, $email->getTo());
        /** @var NamedAddress[] $namedAddresses */
        $namedAddresses = $email->getTo();
        $this->assertInstanceOf(NamedAddress::class, $namedAddresses[0]);
        $this->assertSame('zeze', $namedAddresses[0]->getName());
        $this->assertSame('jf@marche.be', $namedAddresses[0]->getAddress());

    }
}
