<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Transport\Telegram;

use App\Shared\Notifier\Channel\MessageInterface;
use App\Shared\Notifier\Channel\Telegram\MessageInterface as TelegramMessageInterface;
use App\Shared\Notifier\Transport\Exception\MessageSendException;
use App\Shared\Notifier\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @template T of MessageInterface
 * @template-implements TransportInterface<T>
 *
 * @see https://core.telegram.org/bots/api
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Transport implements TransportInterface
{
    private const string HOST = 'api.telegram.org';

    public function __construct(
        private HttpClientInterface $client,
        private string $token,
    ) {}

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof TelegramMessageInterface;
    }

    public function send(MessageInterface $message): void
    {
        if (!$message instanceof TelegramMessageInterface) {
            throw new \LogicException(sprintf(
                'The message must be an instance of %s (%s given).',
                TelegramMessageInterface::class,
                $message::class,
            ));
        }

        $endpoint = sprintf('https://%s/bot%s/sendMessage', self::HOST, $this->token);

        $response = $this->client->request('POST', $endpoint, [
            'json' => [
                'chat_id' => $message->getChatId(),
                'text' => preg_replace('/([_*\[\]()~`>#+\-=|{}.!])/', '\\\\$1', $message->getContent()),
                'parse_mode' => $message->getParseMode(),
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            /** @var array{description: string, error_code: string} $result */
            $result = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);

            throw new MessageSendException(sprintf(
                'Unable to post the Telegram message: %s (code %s)',
                $result['description'],
                $result['error_code'],
            ));
        }
    }
}
