<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 28.08.16
 * Time: 13:57
 */

namespace Migrations;


class RemoveUserPriceInfo extends UserMigration {

    public function apply() {
        $this->forEachUser(function ($user) {
            unset($user['price']);
            return $user;
        }, function ($user) {
            return isset($user['price']);
        });
    }
}