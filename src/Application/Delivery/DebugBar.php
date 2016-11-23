<?php
namespace Application\Delivery;

use DebugBar\DebugBar as CoreDebugBar;

class DebugBar extends CoreDebugBar
{
    public function addCollectors(array $collectors)
    {
        foreach ($collectors as $collector) {
            $this->addCollector($collector);
        }
    }
}
