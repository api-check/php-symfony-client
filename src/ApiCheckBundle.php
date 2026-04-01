<?php

declare(strict_types=1);

namespace ApiCheck\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ApiCheckBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
