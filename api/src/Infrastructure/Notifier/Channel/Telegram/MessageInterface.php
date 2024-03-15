<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\Channel\Telegram;

use App\Infrastructure\Notifier\Channel\MessageInterface as BaseMessageInterface;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
interface MessageInterface extends BaseMessageInterface
{
    public const PARSE_MODE_HTML = 'HTML';
    public const PARSE_MODE_MARKDOWN = 'Markdown';
    public const PARSE_MODE_MARKDOWN_V2 = 'MarkdownV2';

    /**
     * @return non-empty-string
     */
    public function getChatId(): string;

    /**
     * @return self::PARSE_MODE_*
     */
    public function getParseMode(): string;
}
