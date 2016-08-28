<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 28.08.16
 * Time: 17:10
 */

namespace Migrations;


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