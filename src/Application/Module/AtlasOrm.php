<?php
declare(strict_types=1);

namespace Application\Module;

use Atlas\Orm\AtlasContainer;
use Aura\Di\Container;
use Cadre\AtlasOrmDebugBarBridge\AtlasContainer as DebugAtlasContainer;
use Cadre\AtlasOrmDebugBarBridge\AtlasOrmCollector;
use Cadre\Module\Module;

class AtlasOrm extends Module
{
    public function define(Container $di)
    {
        $atlasContainerClass = AtlasContainer::class;
        if ($this->loader()->loaded(DebugBar::class)) {
            $atlasContainerClass = DebugAtlasContainer::class;
        }

        $di->set('atlas:container', $di->lazyNew($atlasContainerClass));
        $di->set('atlas', $di->lazyGetCall('atlas:container', 'getAtlas'));

        $conn = include(__ROOTDIR__ . '/config/conn.php');

        $di->params[$atlasContainerClass] = [
            'dsn'        => $conn[0],
            'username'   => $conn[1],
            'password'   => $conn[2],
            'options'    => [],
            'attributes' => [],
        ];

        $pattern = __DIR__ . '/../Persistence/DataSource/*/*Mapper.php';
        $mappers = glob($pattern);
        foreach ($mappers as $i => $file) {
            $mappers[$i] = 'Application\\Persistence\\'
                         . str_replace('/', '\\', substr($file, strpos($file, 'DataSource/'), -4));
        }

        $di->setters[$atlasContainerClass]['setMappers'] = $mappers;
    }
}
