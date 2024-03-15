<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer\Symfony\Test;

use App\Contracts\Mailer\MessageInterface;
use App\Infrastructure\Mailer\File;
use App\Infrastructure\Mailer\Message;
use App\Infrastructure\Mailer\Symfony\MessageConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Email;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(MessageConverter::class)]
final class MessageConverterTest extends TestCase
{
    public function testSuccess(): void
    {
        $message = (new Message())
            ->priority($priority = MessageInterface::PRIORITY_HIGH)
            ->sender($sender = 'sender@example.com')
            ->from($from = 'from@example.com')
            ->to($to = 'to@example.com')
            ->cc($cc = 'cc@example.com')
            ->bcc($bcc = 'bcc@example.com')
            ->replyTo($replyTo = 'reply-to@example.com')
            ->returnPath($returnPath = 'return-path@example.com')
            ->headers($headers = ['Test' => 'Value'])
            ->subject($subject = 'reply-to@example.com')
            ->text($text = 'Test plain text body')
            ->html($html = 'Test plain text body')
            ->date($date = new \DateTimeImmutable())
            ->attach($attach = File::fromContent('Content', 'text.txt', 'plain/text'));

        $email = (new Email())
            ->priority($priority)
            ->sender($sender)
            ->from($from)
            ->to($to)
            ->cc($cc)
            ->bcc($bcc)
            ->replyTo($replyTo)
            ->returnPath($returnPath)
            ->subject($subject)
            ->text($text, $message->getCharset())
            ->html($html, $message->getCharset())
            ->date($date)
            ->attach((string) $attach->getContent(), $attach->getName(), $attach->getContentType());

        $emailHeaders = $email->getHeaders();
        foreach ($headers as $name => $value) {
            $emailHeaders->addTextHeader($name, $value);
        }

        $converted = MessageConverter::toEmail($message);

        self::assertEquals($email, $converted);
    }

    public function testEmpty(): void
    {
        $message = new Message();
        $converted = MessageConverter::toEmail($message);

        self::assertEquals((new Email())->priority(MessageInterface::PRIORITY_NORMAL), $converted);
    }
}
