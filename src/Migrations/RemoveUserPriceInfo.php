<?php

namespace Migrations;

use Migrations\Common\UserMigration;

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