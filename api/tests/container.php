<?php

declare(strict_types=1);

use App\Infrastructure\Kernel;

$appKernel = new Kernel('tests', false);
$appKernel->boot();

return $appKernel->getContainer();
