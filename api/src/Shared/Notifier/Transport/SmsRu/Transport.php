<?php

declare(strict_types=1);

namespace App\Shared\Notifier\Transport\SmsRu;

use App\Shared\Notifier\Channel\MessageInterface;
use App\Shared\Notifier\Channel\Sms\MessageInterface as SmsMessageInterface;
use App\Shared\Notifier\Transport\Exception\MessageSendException;
use App\Shared\Notifier\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @template T of MessageInterface
 * @template-implements TransportInterface<T>
 *
 * @see https://sms.ru/api/send
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
final readonly class Transport implements TransportInterface
{
    private const string URL = 'https://sms.ru/sms/send';

    public function __construct(
        private HttpClientInterface $client,
        private string $apiId,
    ) {}

    #[\Override]
    public function supports(MessageInterface $message): bool
    {
        return $message instanceof SmsMessageInterface;
    }

    #[\Override]
    public function send(MessageInterface $message): void
    {
        if (!$message instanceof SmsMessageInterface) {
            throw new \LogicException(sprintf(
                'The message must be an instance of %s (%s given).',
                SmsMessageInterface::class,
                $message::class,
            ));
        }

        $response = $this->client->request('GET', self::URL . http_build_query([
            'api_id' => $this->apiId,
            'to' => '+' . trim($message->getPhone(), '+'),
            'text' => $message->getContent(),
            'json' => 1,
        ]));

        if ($response->getStatusCode() !== 200) {
            throw new MessageSendException(sprintf(
                'Sending error with status code %s',
                $response->getStatusCode(),
            ));
        }

        /** @var array{status_code: string, status_text: string} $result */
        $result = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);
        $statusCode = $result['status_code'];

        if ($statusCode < 100 || $statusCode > 103) {
            throw new MessageSendException(sprintf(
                'Unable to post the message: %s (code %s)',
                $result['status_text'],
                $result['status_code'],
            ));
        }
    }
}
