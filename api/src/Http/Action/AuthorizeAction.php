<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Response\HtmlResponse;
use App\Shared\Template\TemplateInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Route('/authorize', name: 'authorize', methods: ['GET'])]
final class AuthorizeAction
{
    public function __construct(
        private readonly TemplateInterface $template
    ) {
    }

    public function __invoke(): HtmlResponse
    {
        return new HtmlResponse($this->template->render('authorize.html.twig'), 200);
    }
}
