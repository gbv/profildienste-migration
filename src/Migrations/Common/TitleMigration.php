<?php

namespace Migrations\Common;


abstract class TitleMigration extends CollectionMigration {

    public function forEachTitle(callable $func, callable $applicableFunc) {
        $this->forEachElement($func, $applicableFunc);
    }
}