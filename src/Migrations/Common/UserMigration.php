<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 27.09.16
 * Time: 15:57
 */

namespace Migrations\Common;


abstract class UserMigration extends CollectionMigration{

    public function forEachUser(callable $func, callable $applicableFunc) {
        $this->forEachElement($func, $applicableFunc);
    }
}