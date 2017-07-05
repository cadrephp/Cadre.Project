<?php
declare(strict_types=1);

namespace Application\Module;

use Aura\Di\Container;
use Cadre\Module\Module;
use Application\Delivery\DefaultInput;
use Application\Delivery\DefaultResponder;
use Psr7Middlewares\Middleware\AttributeMapper;
use Psr7Middlewares\Middleware\Robots;
use Psr7Middlewares\Middleware\TrailingSlash;
use Radar\Adr\Handler\RoutingHandler;
use Radar\Adr\Handler\ActionHandler;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response;

class Core extends Module
{
    public function require()
    {
        return [
            Twig::class,
        ];
    }

    public function requireDevelopment()
    {
        return [
            DebugBar::class,
        ];
    }

    public function define(Container $di)
    {
        /** DefaultResponder */

        $di->params[DefaultResponder::class] = [
            'twig' => $di->lazyGet('twig:environment'),
            'debugbar' => null,
        ];

        if ($this->loader()->loaded(DebugBar::class)) {
            $di->params[DefaultResponder::class]['debugbar'] = $di->lazyGet('debugbar');
        }

        /** ExceptionHandler */

        $di->params[ExceptionHandler::class] = [
            'exceptionResponse' => $di->lazyNew(Response::class),
        ];

        /** Robots */

        $di->params[Robots::class] = [
            'allow' => !$this->loader()->isEnv('development'),
        ];

        /** TrailingSlash */

        $di->params[TrailingSlash::class] = [
            'addSlash' => true,
        ];

        $di->setters[TrailingSlash::class] = [
            'redirect' => 301,
        ];

        /** AttributeMapper */

        $di->params[AttributeMapper::class] = [
            'mapping' => [
            ],
        ];
    }

    public function modify(Container $di)
    {
        $adr = $di->get('radar/adr:adr');

        $adr->middle(ResponseSender::class);
        $adr->middle(Robots::class);
        $adr->middle(ExceptionHandler::class);
        $adr->middle(TrailingSlash::class);
        $adr->middle(AttributeMapper::class);
        $adr->middle(RoutingHandler::class);
        $adr->middle(ActionHandler::class);

        $adr->input(DefaultInput::class);
        $adr->responder(DefaultResponder::class);
    }
}
