<?php

namespace Migrations;


use Migrations\Common\UserMigration;

class RemovePageSizeSetting extends UserMigration {

    public function apply() {
        $this->forEachUser(function ($user) {

            $settings = $user['settings'];
            unset($settings['pagesize']);
            $user['settings'] = $settings;
            return $user;

        }, function ($user) {
            return isset($user['settings']['pagesize']);
        });
    }
}