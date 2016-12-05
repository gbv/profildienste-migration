<?php

namespace Migrations;

use Migrations\Common\UserMigration;

class ChangeLieftToSupplier extends UserMigration {

    public function apply() {
        $this->forEachUser(function ($user) {

            $user['suppliers'] = [
                [
                    'name' => 'N.N.',
                    'value' => 'nn'
                ]
            ];

            $user['defaults']['supplier'] = 'nn';

            unset($user['defaults']['lieft']);

            return $user;
        }, function ($user){
            return !isset($user['suppliers']);
        });
    }
}