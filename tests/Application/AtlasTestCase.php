<?php
declare(strict_types=1);

namespace Application;

use Atlas\Orm\AtlasContainer;
use Aura\SqlQuery\QueryFactory;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\Exception;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Phinx\Migration\Manager\Environment;
use PHPUnit\DbUnit\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

abstract class AtlasTestCase extends TestCase
{
    private static $atlasContainer = null;
    private static $atlas = null;
    private static $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $conn = null;

    public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$atlasContainer === null) {
                self::$atlasContainer = new AtlasContainer('sqlite::memory:');
                self::$atlasContainer->setMappers($this->getMappers());
            }
            if (self::$atlas === null) {
                self::$atlas = self::$atlasContainer->getAtlas();
            }
            if (self::$pdo === null) {
                self::$pdo = self::$atlasContainer->getConnectionLocator()->getDefault()->getPdo();
                $environment = new Environment('testing', ['connection' => self::$pdo]);
                $phinxDir = __ROOTDIR__ . '/config/db';
                $config = new Config([
                    'paths' => [
                        'migrations' => $phinxDir . '/migrations',
                        'seeds' => $phinxDir . '/seeds',
                    ],
                ]);
                $input = new StringInput('');
                $output = new NullOutput();
                $manager = new Manager($config, $input, $output);
                $manager->setEnvironments(['testing' => $environment]);
                $manager->migrate('testing');
                $manager->seed('testing');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }

        return $this->conn;
    }

    public function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }

    public function atlas()
    {
        return self::$atlas;
    }

    public function pdo()
    {
        return self::$pdo;
    }

    public function extendedPdo()
    {
        return new ExtendedPdo(self::$pdo);
    }

    public function queryFactory()
    {
        return new QueryFactory('sqlite', QueryFactory::COMMON);
    }

    protected function getMappers()
    {
        $pattern = __ROOTDIR__ . '/src/AtlasOrm/DataSource/*/*Mapper.php';
        $mappers = glob($pattern);
        foreach ($mappers as $i => $file) {
            $mappers[$i] = 'DateCheckPro\\AtlasOrm\\'
                         . str_replace('/', '\\', substr($file, strpos($file, 'DataSource/'), -4));
        }
        return $mappers;
    }
}
