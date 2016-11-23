<?php
date_default_timezone_set('UTC');
$loader = @include __DIR__ . '/../vendor/autoload.php';

if (false === $loader) {
    die(<<<'EOT'
You must set up the project dependencies by running the following commands:

curl -s http://getcomposer.org/installer | php
php composer.phar install

EOT
    );
}
