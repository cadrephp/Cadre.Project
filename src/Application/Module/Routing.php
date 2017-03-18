<?php
declare(strict_types=1);

namespace Application\Module;

use Aura\Di\Container;
use Cadre\Module\Module;

class Routing extends Module
{
    public function define(Container $di)
    {
    }

    public function modify(Container $di)
    {
        $adr = $di->get('radar/adr:adr');

        $adr->get('Home', '/', function () {
            return ['success' => true];
        })->defaults(['_view' => 'home.html.twig']);
    }
}
