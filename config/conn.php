<?php
$phinx = include(__DIR__ . '/phinx.php');
$default = $phinx['environments']['default_database'];
$env = $phinx['environments'][$default];

if (0 === strcmp('sqlite', $env['adapter'])) {
    if (empty($env['name']) && true === $env['memory']) {
        $env['name'] = ':memory:';
    }
    return [
        sprintf('%s:%s', $env['adapter'], $env['name']),
        null,
        null
    ];
} else {
    return [
        sprintf('%s:dbname=%s;host=%s', $env['adapter'], $env['name'], $env['host']),
        $env['user'],
        $env['pass']
    ];
}
