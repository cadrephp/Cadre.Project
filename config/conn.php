<?php
$phinx = include(__DIR__ . '/phinx.php');
$default = $phinx['environments']['default_database'];
$env = $phinx['environments'][$default];

return [
    sprintf('%s:dbname=%s;host=%s', $env['adapter'], $env['name'], $env['host']),
    $env['user'],
    $env['pass']
];
