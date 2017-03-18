<?php
declare(strict_types=1);

namespace Application\Module;

use Aura\Di\Container;
use Cadre\AtlasOrmDebugBarBridge\AtlasOrmCollector;
use Cadre\Module\Module;
use DebugBar\Bridge\Twig\TraceableTwigEnvironment;
use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\Bridge\Twig\TwigCollector;
use Application\Delivery\DebugBar as LocalDebugBar;
use Twig_Environment;

class DebugBar extends Module
{
    public function define(Container $di)
    {
        /** Services */

        $di->set('debugbar', $di->lazyNew(LocalDebugBar::class));
        $di->set('debugbar:tdc', $di->lazyNew(TimeDataCollector::class));

        /** Debug Bar */

        $collectors = [
            $di->lazyNew(PhpInfoCollector::class),
            $di->lazyNew(MessagesCollector::class),
            $di->lazyNew(RequestDataCollector::class),
            $di->lazyNew(MemoryCollector::class),
            $di->lazyNew(ExceptionsCollector::class),
        ];

        if ($this->loader()->loaded(Twig::class)) {
            $di->params[TraceableTwigEnvironment::class] = [
                'twig' => $di->lazyNew(Twig_Environment::class),
                'timeDataCollector' => $di->lazyGet('debugbar:tdc'),
            ];

            $di->params[TwigCollector::class] = [
                'twig' => $di->lazyGet('twig:environment'),
            ];

            $collectors[] = $di->lazyNew(TwigCollector::class);
        }

        if ($this->loader()->loaded(AtlasOrm::class)) {
            $di->params[AtlasOrmCollector::class] = [
                'atlasContainer' => $di->lazyGet('atlas:container'),
                'timeCollector' => $di->lazyGet('debugbar:tdc'),
            ];

            $collectors[] = $di->lazyNew(AtlasOrmCollector::class);
        }

        $collectors[] = $di->lazyGet('debugbar:tdc');

        $di->setters[LocalDebugBar::class]['addCollectors'] = $di->lazyArray($collectors);
    }
}
