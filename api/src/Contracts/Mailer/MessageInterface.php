<?php

declare(strict_types=1);

namespace App\Contracts\Mailer;

use DateTimeInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface MessageInterface
{
    public const PRIORITY_HIGHEST = 1;
    public const PRIORITY_HIGH = 2;
    public const PRIORITY_NORMAL = 3;
    public const PRIORITY_LOW = 4;
    public const PRIORITY_LOWEST = 5;

    /**
     * Returns the charset of this message.
     *
     * @return string the charset of this message
     */
    public function getCharset(): string;

    /**
     * Returns the message sender email address.
     *
     * @return array<string, string>|string|string[]|null the sender email address
     */
    public function getFrom(): array|string|null;

    /**
     * Returns the message recipient(s) email address.
     *
     * @return array<string, string>|string|string[]|null the message recipients email address
     */
    public function getTo(): array|string|null;

    /**
     * Returns the reply-to address of this message.
     *
     * @return array<string, string>|string|string[]|null the reply-to address of this message
     */
    public function getReplyTo(): array|string|null;

    /**
     * Returns the Cc (additional copy receiver) addresses of this message.
     *
     * @return array<string, string>|string|string[]|null the Cc (additional copy receiver) addresses of this message
     */
    public function getCc(): array|string|null;

    /**
     * Returns the Bcc (hidden copy receiver) addresses of this message.
     *
     * @return array<string, string>|string|string[]|null the Bcc (hidden copy receiver) addresses of this message
     */
    public function getBcc(): array|string|null;

    /**
     * Returns the message subject.
     *
     * @return string|null the message subject
     */
    public function getSubject(): ?string;

    /**
     * Returns the date when the message was sent, or null if it was not set.
     *
     * @return DateTimeInterface|null the date when the message was sent
     */
    public function getDate(): ?DateTimeInterface;

    /**
     * Returns the priority of this message.
     *
     * @return self::PRIORITY_* The priority value as integer in range: `1..5`,
     * where 1 is the highest priority and 5 is the lowest.
     */
    public function getPriority(): int;

    /**
     * Returns the return-path (the bounce address) of this message.
     *
     * @return string|null the bounce email address
     */
    public function getReturnPath(): ?string;

    /**
     * Returns the message actual sender email address.
     *
     * @return string|null the actual sender email address
     */
    public function getSender(): ?string;

    /**
     * Returns the message text body.
     *
     * @return string|null the message text body
     */
    public function getText(): ?string;

    /**
     * Returns the message HTML body.
     *
     * @return string|null the message HTML body
     */
    public function getHtml(): ?string;

    /**
     * Returns the attachments.
     *
     * @return list<FileInterface> $file
     */
    public function getAttachments(): array;

    /**
     * Returns the headers.
     *
     * @return array<string, string[]> $headers the headers in format: `[name => value]`
     */
    public function getHeaders(): array;
}
