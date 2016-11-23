<?php
$copyFolders = [
    __DIR__ . '/../vendor/maximebf/debugbar/src/DebugBar/Resources' =>
        __DIR__ . '/../web/debugbar'
];

foreach ($copyFolders as $from => $to) {
    passthru(sprintf('cp -r %s %s', escapeshellarg($from), escapeshellarg($to)));
}
