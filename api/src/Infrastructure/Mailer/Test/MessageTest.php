<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer\Test;

use App\Contracts\Mailer\MessageInterface;
use App\Infrastructure\Mailer\File;
use App\Infrastructure\Mailer\Message;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Message::class)]
final class MessageTest extends TestCase
{
    private Message $message;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->message = new Message();
    }

    public static function charsetDataProvider(): \Iterator
    {
        yield ['utf-8'];
        yield ['iso-8859-2'];
    }

    public static function addressesDataProvider(): \Iterator
    {
        yield [
            'foo@example.com',
            'foo@example.com',
        ];
        yield [
            ['foo@example.com', 'bar@example.com'],
            ['foo@example.com', 'bar@example.com'],
        ];
        yield [
            ['foo@example.com' => 'foo'],
            ['foo@example.com' => 'foo'],
        ];
        yield [
            ['foo@example.com' => 'foo', 'bar@example.com' => 'bar'],
            ['foo@example.com' => 'foo', 'bar@example.com' => 'bar'],
        ];
    }

    public static function priorityDataProvider(): \Iterator
    {
        yield [MessageInterface::PRIORITY_HIGHEST];
        yield [MessageInterface::PRIORITY_HIGH];
        yield [MessageInterface::PRIORITY_NORMAL];
        yield [MessageInterface::PRIORITY_LOW];
        yield [MessageInterface::PRIORITY_LOWEST];
    }

    public static function headerDataProvider(): \Iterator
    {
        yield ['X-Foo', 'Bar', ['Bar']];
        yield ['X-Fuzz', ['Bar', 'Baz'], ['Bar', 'Baz']];
    }

    public function testSubject(): void
    {
        $subject = 'Test subject';
        $message = $this->message->subject($subject);

        self::assertNotSame($message, $this->message);
        self::assertSame($subject, $message->getSubject());
    }

    #[DataProvider('charsetDataProvider')]
    public function testCharset(string $charset): void
    {
        $message = $this->message->charset($charset);

        self::assertNotSame($message, $this->message);
        self::assertSame($charset, $message->getCharset());
    }

    /**
     * @param array<string, string> $from
     */
    #[DataProvider('addressesDataProvider')]
    public function testFrom(array|string $from, array|string $expected): void
    {
        $message = $this->message->from($from);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getFrom());
    }

    /**
     * @param array<string, string> $to
     */
    #[DataProvider('addressesDataProvider')]
    public function testTo(array|string $to, array|string $expected): void
    {
        $message = $this->message->to($to);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getTo());
    }

    /**
     * @param array<string, string> $replyTo
     */
    #[DataProvider('addressesDataProvider')]
    public function testReplyTo(array|string $replyTo, array|string $expected): void
    {
        $message = $this->message->replyTo($replyTo);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getReplyTo());
    }

    /**
     * @param array<string, string> $cc
     */
    #[DataProvider('addressesDataProvider')]
    public function testCc(array|string $cc, array|string $expected): void
    {
        $message = $this->message->cc($cc);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getCc());
    }

    /**
     * @param array<string, string> $bcc
     */
    #[DataProvider('addressesDataProvider')]
    public function testBcc(array|string $bcc, array|string $expected): void
    {
        $message = $this->message->bcc($bcc);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getBcc());
    }

    public function testDate(): void
    {
        $date = new \DateTimeImmutable();
        $message = $this->message->date($date);

        self::assertNotSame($message, $this->message);
        self::assertNotSame($date, $message->getDate());
        self::assertInstanceOf(\DateTimeImmutable::class, $message->getDate());
        self::assertSame($date->getTimestamp(), $message
            ->getDate()
            ->getTimestamp());
    }

    /**
     * @param MessageInterface::PRIORITY_* $priority
     */
    #[DataProvider('priorityDataProvider')]
    public function testPriority(int $priority): void
    {
        $message = $this->message->priority($priority);

        self::assertNotSame($message, $this->message);
        self::assertSame($priority, $message->getPriority());
    }

    public function testReturnPath(): void
    {
        $address = 'foo@exmaple.com';
        $message = $this->message->returnPath($address);

        self::assertNotSame($message, $this->message);
        self::assertSame($address, $message->getReturnPath());
    }

    public function testSender(): void
    {
        $address = 'foo@exmaple.com';
        $message = $this->message->sender($address);

        self::assertNotSame($message, $this->message);
        self::assertSame($address, $message->getSender());
    }

    /**
     * @param string|string[] $value
     */
    #[DataProvider('headerDataProvider')]
    public function testHeader(string $name, array|string $value, array $expected): void
    {
        $message = $this->message->header($name, $value);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getHeaders()[$name]);
    }

    /**
     * @param string|string[] $value
     */
    #[DataProvider('headerDataProvider')]
    public function testHeaders(string $name, array|string $value, array $expected): void
    {
        $message = $this->message->headers([$name => $value]);

        self::assertNotSame($message, $this->message);
        self::assertSame($expected, $message->getHeaders()[$name]);
    }

    public function testTextBody(): void
    {
        $body = 'Plain text';
        $message = $this->message->text($body);

        self::assertNotSame($message, $this->message);
        self::assertSame($body, $message->getText());
    }

    public function testHtmlBody(): void
    {
        $body = '<p>HTML content</p>';
        $message = $this->message->html($body);

        self::assertNotSame($message, $this->message);
        self::assertSame($body, $message->getHtml());
    }

    public function testSerialize(): void
    {
        $message = $this->message
            ->to('to@example.com')
            ->from('from@example.com')
            ->subject('Alternative Body Test')
            ->text('Test plain text body');

        self::assertNotSame($this->message, $message);

        $serializedMessage = serialize($message);
        self::assertNotEmpty($serializedMessage, 'Unable to serialize message!');

        /** @var Message $unserializedMessaage */
        $unserializedMessaage = unserialize($serializedMessage);
        self::assertEquals($message, $unserializedMessaage, 'Unable to unserialize message!');
    }

    public function testAttachFile(): void
    {
        $file = File::fromPath(__FILE__, 'test.php', 'application/x-php');

        $message = $this->message
            ->to('to@example.com')
            ->from('from@example.com')
            ->subject('Attach File Test')
            ->text('Attach File Test body')
            ->attach($file);

        self::assertNotSame($this->message, $message);
        $this->assertAttachment($message, $file, false);
    }

    public function testAttachContent(): void
    {
        $file = File::fromContent('Test attachment content', 'test.txt', 'text/plain');

        $message = $this->message
            ->to('to@example.com')
            ->from('from@example.com')
            ->subject('Attach Content Test')
            ->text('Attach Content Test body')
            ->attach($file);

        self::assertNotSame($this->message, $message);
        $this->assertAttachment($message, $file, true);
    }

    public function testEmbedFile(): void
    {
        $path = $this->createImageFile('embed-file.png', 'Embed Image File');
        $file = File::fromPath($path, basename($path), 'image/png');

        $message = $this->message
            ->to('to@example.com')
            ->from('from@example.com')
            ->subject('Embed File Test')
            ->html('Embed image: <img src="' . $file->getCid() . '" alt="pic">')
            ->attach($file);

        self::assertNotSame($this->message, $message);
        $this->assertAttachment($message, $file, false);
    }

    public function testEmbedContent(): void
    {
        $path = $this->createImageFile('embed-file.png', 'Embed Image File');
        $file = File::fromContent(file_get_contents($path), basename($path), 'image/png');

        $message = $this->message
            ->to('to@example.com')
            ->from('from@example.com')
            ->subject('Embed Content Test')
            ->html('Embed image: <img src="' . $file->getCid() . '" alt="pic">')
            ->attach($file);

        self::assertNotSame($this->message, $message);
        $this->assertAttachment($message, $file, true);
    }

    public function testDefaultGetters(): void
    {
        self::assertSame('utf-8', $this->message->getCharset());
        self::assertNull($this->message->getFrom());
        self::assertNull($this->message->getTo());
        self::assertNull($this->message->getReplyTo());
        self::assertNull($this->message->getCc());
        self::assertNull($this->message->getBcc());
        self::assertNull($this->message->getSubject());
        self::assertNull($this->message->getText());
        self::assertNull($this->message->getHtml());
        self::assertSame([], $this->message->getHeaders());
        self::assertSame([], $this->message->getAttachments());
        self::assertNull($this->message->getDate());
        self::assertSame(MessageInterface::PRIORITY_NORMAL, $this->message->getPriority());
        self::assertNull($this->message->getReturnPath());
        self::assertNull($this->message->getSender());
    }

    public function testImmutability(): void
    {
        $file = File::fromContent('Test attachment content', 'test.txt', 'text/plain');

        self::assertNotSame($this->message, $this->message->charset('utf-8'));
        self::assertNotSame($this->message, $this->message->from('from@example.com'));
        self::assertNotSame($this->message, $this->message->to('to@example.com'));
        self::assertNotSame($this->message, $this->message->replyTo('reply-to@example.com'));
        self::assertNotSame($this->message, $this->message->cc('cc@example.com'));
        self::assertNotSame($this->message, $this->message->bcc('bcc@example.com'));
        self::assertNotSame($this->message, $this->message->subject('subject'));
        self::assertNotSame($this->message, $this->message->text('text'));
        self::assertNotSame($this->message, $this->message->html('html'));
        self::assertNotSame($this->message, $this->message->attach($file));
        self::assertNotSame($this->message, $this->message->header('name', 'value'));
        self::assertNotSame($this->message, $this->message->headers([]));
        self::assertNotSame($this->message, $this->message->date(new \DateTimeImmutable()));
        self::assertNotSame($this->message, $this->message->priority(MessageInterface::PRIORITY_NORMAL));
        self::assertNotSame($this->message, $this->message->returnPath('bounce@example.com'));
        self::assertNotSame($this->message, $this->message->sender('sender@example.com'));
    }

    protected function getTestFilePath(): string
    {
        return \dirname(__DIR__) . '/../../../var'
            . \DIRECTORY_SEPARATOR;
    }

    private function assertAttachment(Message $message, File $file, bool $checkContent): void
    {
        $attachment = $message
            ->getAttachments()[0];

        self::assertInstanceOf(File::class, $attachment);

        if ($checkContent) {
            self::assertSame($file->getContent(), $attachment->getContent(), 'Invalid content!');
        }

        self::assertSame($file->getName(), $attachment->getName(), 'Invalid file name!');

        self::assertSame($file->getContentType(), $attachment->getContentType(), 'Invalid content type!');
    }

    private function createImageFile(string $fileName = 'test.png', string $text = 'Test Image'): string
    {
        if (!\function_exists('imagepng')) {
            self::markTestSkipped('GD lib required.');
        }

        $fileFullName = $this->getTestFilePath() . \DIRECTORY_SEPARATOR . $fileName;
        $image = imagecreatetruecolor(120, 20);

        if ($image === false) {
            throw new \RuntimeException('Unable create a new true color image.');
        }

        $textColor = imagecolorallocate($image, 233, 14, 91);
        imagestring($image, 1, 5, 5, $text, $textColor);
        imagepng($image, $fileFullName);
        imagedestroy($image);

        return $fileFullName;
    }
}
