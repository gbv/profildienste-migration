<?php

namespace Migrations\Common;

use Logging;

/**
 * Base class for all migrations
 *
 * Class Migration
 * @package Migrations
 */
abstract class Migration {

    use Logging;

    /**
     * Applies the migration
     *
     * @return void
     */
    public abstract function apply();
}