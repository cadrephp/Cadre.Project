<?php
declare(strict_types=1);

namespace Application\Module;

use Aura\Cli\CliFactory;
use Aura\Di\Container;
use Cadre\Module\Module;

class Cli extends Module
{
    public function define(Container $di)
    {
        $di->set('aura:cli/factory', $di->lazyNew(CliFactory::class));
        $di->set('aura:cli/context', $di->lazyGetCall('aura:cli/factory', 'newContext', $GLOBALS));
        $di->set('aura:cli/stdio', $di->lazyGetCall('aura:cli/factory', 'newStdio'));
    }

    public function modify(Container $di)
    {
        $adr = $di->get('cadre:cliadr/adr');

        // Register commands here
    }
}
