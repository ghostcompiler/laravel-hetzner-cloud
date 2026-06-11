<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    // Target PHP 7.4 rules to avoid PHP 8.0+ changes
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_74,
    ]);
};
