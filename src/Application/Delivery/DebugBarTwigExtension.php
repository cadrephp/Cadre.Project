<?php
declare(strict_types=1);

namespace Application\Delivery;

use DebugBar\DebugBar;
use Twig_Extension;
use Twig_Function;

class DebugBarTwigExtension extends Twig_Extension
{
    private $renderer;

    public function __construct(DebugBar $debugbar = null)
    {
        if (isset($debugbar)) {
            $this->renderer = $debugbar->getJavascriptRenderer();
            $this->renderer->setBaseUrl('/debugbar');
        }
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('dbg_render', [$this, 'render'], ['is_safe' => ['html']]),
            new Twig_Function('dbg_renderHead', [$this, 'renderHead'], ['is_safe' => ['html']]),
        );
    }

    public function render()
    {
        if (isset($this->renderer)) {
            return $this->renderer->render();
        }
    }

    public function renderHead()
    {
        if (isset($this->renderer)) {
            return $this->renderer->renderHead();
        }
    }

    public function getName()
    {
        return 'debugbar_extension';
    }
}
