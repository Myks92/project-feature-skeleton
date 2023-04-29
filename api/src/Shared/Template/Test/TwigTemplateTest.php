<?php

declare(strict_types=1);

namespace App\Shared\Template\Test;

use App\Shared\Template\TwigTemplate;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * @internal
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[CoversClass(TwigTemplate::class)]
final class TwigTemplateTest extends TestCase
{
    public function testRender(): void
    {
        $twig = $this->createMock(Environment::class);
        $twig->expects(self::once())->method('render')->with(
            self::equalTo('home.html.twig'),
            self::equalTo(['status' => 'OK']),
        )->willReturn($html = '<html lang="en"><body></body></html>');

        $template = new TwigTemplate($twig);

        $homeTemplate = $template->render('home.html.twig', ['status' => 'OK']);

        self::assertSame($html, $homeTemplate);
    }
}
