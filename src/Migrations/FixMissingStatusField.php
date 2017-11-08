<?php

namespace Migrations;


use Migrations\Common\TitleMigration;

class FixMissingStatusField extends TitleMigration {

    /**
     * Applies the migration
     *
     * @return void
     */
    public function apply() {
        $this->forEachTitle(function ($title){

            if (is_null($title['status'])) {
                $title['status'] = 'normal';
            }

            return $title;
        }, function ($title){
            return !array_key_exists('status', $title);
        });
    }
}