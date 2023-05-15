<?php

declare(strict_types=1);

namespace App\Shared\Mailer\Test\Adapter\Symfony;

use App\Shared\Mailer\Adapter\Symfony\Mailer;
use App\Shared\Mailer\Adapter\Symfony\MessageConverter;
use App\Shared\Mailer\File;
use App\Shared\Mailer\Message;
use App\Shared\Mailer\MessageInterface;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailer;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Mailer::class)]
final class MailerTest extends TestCase
{
    public function testSuccess(): void
    {
        $message = (new Message())
            ->sender('sender@example.com')
            ->from('from@example.com')
            ->to('to@example.com')
            ->cc('cc@example.com')
            ->bcc('bcc@example.com')
            ->replyTo('reply-to@example.com')
            ->returnPath('return-path@example.com')
            ->priority(MessageInterface::PRIORITY_LOWEST)
            ->headers([])
            ->subject('reply-to@example.com')
            ->text('Test plain text body')
            ->html('<p>Test plain text body</p>>')
            ->date(new DateTimeImmutable())
            ->attach(File::fromContent('Content', 'text.txt', 'plain/text'));

        $email = MessageConverter::toEmail($message);

        $adapter = $this->createMock(SymfonyMailer::class);
        $adapter->expects(self::once())->method('send')->with(
            self::equalTo($email),
            self::identicalTo(null),
        );

        $mailer = new Mailer($adapter);

        $mailer->send($message);
    }
}
