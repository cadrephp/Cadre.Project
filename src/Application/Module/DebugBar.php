<?php
declare(strict_types=1);

namespace Application\Module;

use Aura\Di\Container;
use Cadre\AtlasOrmDebugBarBridge\AtlasOrmCollector;
use Cadre\Module\Module;
use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\Bridge\TwigProfileCollector;
use DebugBar\Bridge\Twig\TwigCollector;
use Application\Delivery\DebugBar as LocalDebugBar;
use Application\Delivery\DebugBarTwigExtension;
use Twig_Environment;
use Twig_Profiler_Profile;

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

        $di->params[DebugBarTwigExtension::class] = [
            'debugbar' => $di->lazyGet('debugbar'),
        ];

        if ($this->loader()->loaded(Twig::class)) {
            $di->params[TwigProfileCollector::class] = [
                'profile' => $di->lazyGet('twig:profile'),
            ];

            $collectors[] = $di->lazyNew(TwigProfileCollector::class);
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
