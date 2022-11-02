<?php

declare(strict_types=1);

namespace App\Http\Action;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Maksim Vorozhtsov <myks1992@mail.ru>
 */
#[Route('', name: 'home', methods: ['GET'])]
final class HomeAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['name' => 'API']);
    }
}
