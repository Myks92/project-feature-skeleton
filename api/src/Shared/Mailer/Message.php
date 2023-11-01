<?php

declare(strict_types=1);

namespace App\Shared\Mailer;

use App\Contracts\Mailer\MessageInterface;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class Message implements MessageInterface
{
    private string $charset = 'utf-8';

    /**
     * @var array<string, string>|string|string[]|null
     */
    private null|array|string $from = null;

    /**
     * @var array<string, string>|string|string[]|null
     */
    private null|array|string $to = null;

    /**
     * @var array<string, string>|string|string[]|null
     */
    private null|array|string $replyTo = null;

    /**
     * @var array<string, string>|string|string[]|null
     */
    private null|array|string $cc = null;

    /**
     * @var array<string, string>|string|string[]|null
     */
    private null|array|string $bcc = null;

    private ?string $subject = null;
    private ?string $text = null;
    private ?string $html = null;
    private ?DateTimeInterface $date = null;

    /**
     * @var self::PRIORITY_*
     */
    private int $priority = self::PRIORITY_NORMAL;

    private ?string $returnPath = null;
    private ?string $sender = null;

    /**
     * @var list<File>
     */
    private array $attachments = [];

    /**
     * @var array<string, string[]>
     */
    private array $headers = [];

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function charset(string $charset): self
    {
        $new = clone $this;
        $new->charset = $charset;
        return $new;
    }

    public function getFrom(): null|array|string
    {
        return $this->from;
    }

    /**
     * @param array<string, string>|string|string[] $from
     */
    public function from(array|string $from): self
    {
        $new = clone $this;
        $new->from = self::convertStringsToAddresses($from);
        return $new;
    }

    public function getTo(): null|array|string
    {
        return $this->to;
    }

    /**
     * @param array<string, string>|string|string[] $to
     */
    public function to(array|string $to): self
    {
        $new = clone $this;
        $new->to = self::convertStringsToAddresses($to);
        return $new;
    }

    public function getReplyTo(): null|array|string
    {
        return $this->replyTo;
    }

    /**
     * @param array<string, string>|string|string[] $replyTo
     */
    public function replyTo(array|string $replyTo): self
    {
        $new = clone $this;
        $new->replyTo = self::convertStringsToAddresses($replyTo);
        return $new;
    }

    public function getCc(): null|array|string
    {
        return $this->cc;
    }

    /**
     * @param array<string, string>|string|string[] $cc
     */
    public function cc(array|string $cc): self
    {
        $new = clone $this;
        $new->cc = self::convertStringsToAddresses($cc);
        return $new;
    }

    public function getBcc(): null|array|string
    {
        return $this->bcc;
    }

    /**
     * @param array<string, string>|string|string[] $bcc
     */
    public function bcc(array|string $bcc): self
    {
        $new = clone $this;
        $new->bcc = self::convertStringsToAddresses($bcc);
        return $new;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function subject(string $subject): self
    {
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function date(DateTimeInterface $date): self
    {
        $new = clone $this;
        $new->date = DateTimeImmutable::createFromInterface($date);
        return $new;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param self::PRIORITY_* $priority
     */
    public function priority(int $priority): self
    {
        $new = clone $this;
        $new->priority = $priority;
        return $new;
    }

    public function getReturnPath(): ?string
    {
        return $this->returnPath;
    }

    public function returnPath(string $address): self
    {
        $new = clone $this;
        $new->returnPath = $address;
        return $new;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function sender(string $address): self
    {
        $new = clone $this;
        $new->sender = $address;
        return $new;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function text(string $text): self
    {
        $new = clone $this;
        $new->text = $text;
        return $new;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function html(string $html): self
    {
        $new = clone $this;
        $new->html = $html;
        return $new;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function attach(File $file): self
    {
        $new = clone $this;
        $new->attachments[] = $file;
        return $new;
    }

    /**
     * @return array<string, string[]>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string|string[] $value
     */
    public function header(string $name, array|string $value): self
    {
        $new = clone $this;
        $new->headers[$name] = (array)$value;
        return $new;
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    public function headers(array $headers): self
    {
        $new = clone $this;

        foreach ($headers as $name => $value) {
            $new = $new->header($name, $value);
        }

        return $new;
    }

    /**
     * @param array<string, string>|string|string[] $strings
     *
     * @return array<string, string>|string|string[]
     */
    private static function convertStringsToAddresses(array|string $strings): array|string
    {
        if (\is_string($strings)) {
            return $strings;
        }

        $addresses = [];

        foreach ($strings as $address => $name) {
            if (!\is_string($address)) {
                // email address without name
                $addresses[] = $name;
                continue;
            }

            $addresses[$address] = $name;
        }

        return $addresses;
    }
}
