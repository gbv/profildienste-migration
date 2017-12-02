<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 02.12.17
 * Time: 15:43
 */

namespace Migrations;


use Migrations\Common\TitleMigration;

class ChangeLieftToSupplierTitle extends TitleMigration {

    /**
     * Applies the migration
     *
     * @return void
     */
    public function apply() {
        $this->forEachTitle(function ($title) {
            $title['supplier'] = $title['lieft'];
            unset($title['lieft']);
            return $title;
        }, function ($title) {
            return array_key_exists('lieft', $title);
        });
    }
}