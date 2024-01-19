<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer\Symfony\Test;

use App\Contracts\Mailer\MessageInterface;
use App\Infrastructure\Mailer\Symfony\MessageConverter;
use App\Infrastructure\Mailer\Symfony\SymfonyMailer;
use App\Shared\Mailer\File;
use App\Shared\Mailer\Message;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface as AdapterMailer;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(SymfonyMailer::class)]
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
            ->date(new \DateTimeImmutable())
            ->attach(File::fromContent('Content', 'text.txt', 'plain/text'));

        $email = MessageConverter::toEmail($message);

        $adapter = $this->createMock(AdapterMailer::class);
        $adapter->expects(self::once())->method('send')->with(
            self::equalTo($email),
            self::identicalTo(null),
        );

        $mailer = new SymfonyMailer($adapter);

        $mailer->send($message);
    }
}
