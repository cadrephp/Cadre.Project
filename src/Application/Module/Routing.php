<?php
declare(strict_types=1);

namespace Application\Module;

use Application\Domain\Home;
use Aura\Di\Container;
use Cadre\Module\Module;
use Radar\Adr\Adr;

class Routing extends Module
{
    public function define(Container $di)
    {
    }

    public function modify(Container $di)
    {
        $adr = $di->get('radar/adr:adr');

        if ($adr instanceof Adr) {
            $adr->__call('get', ['Home', '/', Home::class])
                ->defaults(['_view' => 'home.html.twig']);
        }
    }
}
