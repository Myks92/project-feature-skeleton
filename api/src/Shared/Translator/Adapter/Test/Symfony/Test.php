<?php

declare(strict_types=1);

namespace App\Shared\Translator\Adapter\Test\Symfony;

use App\Shared\Translator\Adapter\Symfony\Translator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 *
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(Translator::class)]
final class Test extends TestCase
{
    public function testSuccess(): void
    {
        $adapter = $this->createMock(TranslatorInterface::class);
        $adapter->expects(self::once())->method('trans')->with(
            $id = 'Message %type%',
            $parameters = ['%type%' => 'awesome'],
            $domain = 'app',
            $locale = 'ru',
        )->willReturn('Message awesome');

        $translator = new Translator($adapter);
        $message = $translator->trans(id: $id, parameters: $parameters, domain: $domain, locale: $locale);

        self::assertSame('Message awesome', $message);
    }
}
