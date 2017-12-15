<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 15.12.17
 * Time: 14:52
 */

namespace Migrations;


use Migrations\Common\TitleMigration;

class WrongCoverStats extends TitleMigration {

    /**
     * Applies the migration
     *
     * @return void
     */
    public function apply() {
        $this->forEachTitle(function ($title){
            exit(0);
        }, function ($title){
            $rawISBN = isset($title['004A']['A']) ? $title['004A']['A'] : null;
            if ($rawISBN && isset($title['XX02'])) {
                $match = null;
                if (preg_match('\/api\/cover\/(\d+)', $title['XX02']['md'], $match)) {
                    var_dump($match);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });
    }
}