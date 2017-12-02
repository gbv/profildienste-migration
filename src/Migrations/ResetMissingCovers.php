<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 02.12.17
 * Time: 15:41
 */

namespace Migrations;


use Migrations\Common\TitleMigration;

class ResetMissingCovers extends TitleMigration {

    /**
     * Applies the migration
     *
     * @return void
     */
    public function apply() {
        $this->forEachTitle(function ($title){
            $title['XX02'] = null;
            return $title;
        }, function ($title){
            return $title['XX02'] === false;
        });
    }
}