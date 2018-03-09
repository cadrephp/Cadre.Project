<?php
declare(strict_types=1);

namespace Application\Delivery;

use Psr\Http\Server\MiddlewareInterface;

class RaderPsr15Wrapper
{
    public function __construct(MiddlewareInterface $middleware)
    {

    }
}
