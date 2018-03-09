<?php
declare(strict_types=1);

namespace Application\Module;

use Application\Delivery\DebugBarTwigExtension;
use Aura\Di\Container;
use Cadre\Module\Module;
use DebugBar\Bridge\Twig\TraceableTwigEnvironment;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Extension_Profiler;
use Twig_Loader_Filesystem;
use Twig_Profiler_Profile;
use Twig_SimpleFunction;

class Twig extends Module
{
    public function define(Container $di)
    {
        /** Services */

        $di->set('twig:environment', $di->lazyNew(Twig_Environment::class));
        $di->set('twig:profile', $di->lazyNew(Twig_Profiler_Profile::class));

        /** Twig */

        $di->params[Twig_Loader_Filesystem::class]['paths'] = [
            realpath(__ROOTDIR__ . '/resources/views'),
        ];

        $di->params[Twig_Environment::class] = [
            'loader' => $di->lazyNew(Twig_Loader_Filesystem::class),
            'options' => ['debug' => true],
        ];

        $di->params[Twig_Extension_Profiler::class] = [
            'profile' => $di->lazyGet('twig:profile'),
        ];

        $extensions = [];

        $extensions[] = $di->lazyNew(Twig_Extension_Debug::class);
        $extensions[] = $di->lazyNew(DebugBarTwigExtension::class);

        if ($this->loader()->loaded(DebugBar::class)) {
            $extensions[] = $di->lazyNew(Twig_Extension_Profiler::class);
        }

        $di->setters[Twig_Environment::class]['setExtensions'] = $di->lazyArray($extensions);
    }
}
