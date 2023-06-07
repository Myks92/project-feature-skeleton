<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer\Symfony;

use App\Contracts\Mailer\FileInterface;
use App\Contracts\Mailer\MessageInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File as PartFile;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final class MessageConverter
{
    public static function toEmail(MessageInterface $message): Email
    {
        $email = (new Email())
            ->priority($message->getPriority());

        if (($date = $message->getDate()) !== null) {
            $email->date($date);
        }
        if (!empty($sender = $message->getSender())) {
            $email->sender(...self::convertAddresses($sender));
        }
        if (!empty($from = $message->getFrom())) {
            $email->from(...self::convertAddresses($from));
        }
        if (!empty($to = $message->getTo())) {
            $email->to(...self::convertAddresses($to));
        }
        if (!empty($cc = $message->getCc())) {
            $email->cc(...self::convertAddresses($cc));
        }
        if (!empty($bcc = $message->getBcc())) {
            $email->bcc(...self::convertAddresses($bcc));
        }
        if (!empty($replyTo = $message->getReplyTo())) {
            $email->replyTo(...self::convertAddresses($replyTo));
        }
        if (!empty($returnPath = $message->getReturnPath())) {
            $email->returnPath(...self::convertAddresses($returnPath));
        }
        if (!empty($subject = $message->getSubject())) {
            $email->subject($subject);
        }
        if (!empty($text = $message->getText())) {
            $email->text($text, $message->getCharset());
        }
        if (!empty($html = $message->getHtml())) {
            $email->html($html, $message->getCharset());
        }

        if (!empty($message->getHeaders())) {
            $email->setHeaders(self::convertHeaders($email->getHeaders(), $message->getHeaders()));
        }

        foreach (self::convertAttachments($message->getAttachments()) as $dataPart) {
            $email->addPart($dataPart);
        }

        return $email;
    }

    /**
     * @param array<int|string, string>|string $strings
     *
     * @return Address[]
     */
    private static function convertAddresses(array|string $strings): array
    {
        if (\is_string($strings)) {
            return [new Address($strings)];
        }

        $addresses = [];
        foreach ($strings as $address => $name) {
            if (!\is_string($address)) {
                // email address without name
                $addresses[] = new Address($name);
                continue;
            }
            $addresses[] = new Address($address, $name);
        }

        return $addresses;
    }

    /**
     * @param array<string, string|string[]> $messageHeaders
     */
    private static function convertHeaders(Headers $headers, array $messageHeaders): Headers
    {
        foreach ($messageHeaders as $name => $value) {
            if ($headers->has($name)) {
                $headers->remove($name);
            }
            foreach ((array)$value as $v) {
                $headers->addTextHeader($name, $v);
            }
        }

        return $headers;
    }

    /**
     * @param list<FileInterface> $files
     *
     * @return list<DataPart>
     */
    private static function convertAttachments(array $files): array
    {
        return array_map(static function (FileInterface $file): DataPart {
            $path = $file->getPath();

            $part = new DataPart(
                body: $path === null ? (string)$file->getContent() : new PartFile($path),
                filename: $file->getName(),
                contentType: $file->getContentType()
            );

            if ($file->isEmbed()) {
                $part->asInline();
            }

            return $part;
        }, $files);
    }
}
