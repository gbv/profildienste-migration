<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 27.09.16
 * Time: 15:54
 */

namespace Migrations;


use Migrations\Common\TitleMigration;

class RemoveTitleWatchlistField extends TitleMigration {

    /**
     * Applies the migration
     *
     * @return void
     */
    public function apply() {
        $this->forEachTitle(function ($title){

            if (!is_null($title['watchlist'])) {
                $title['status'] = 'watchlist/'.$title['watchlist'];
            }

            unset($title['watchlist']);

            return $title;
        }, function ($title){
            return array_key_exists('watchlist', $title);
        });
    }
}