<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 28.08.16
 * Time: 13:58
 */

namespace Migrations;

use Logging;

/**
 * Base class for all migrations
 *
 * Class Migration
 * @package Migrations
 */
abstract class Migration {

    use Logging;

    public abstract function apply();
}