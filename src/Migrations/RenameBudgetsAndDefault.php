<?php

namespace Migrations;


use Migrations\Common\UserMigration;

class RenameBudgetsAndDefault extends UserMigration {

    public function apply() {
        $this->forEachUser(function ($user) {

            // get and rename the current budgets
            $budgets = array_map(function ($budget) {
                return [
                    'name' => $budget['c'],
                    'value' => $budget['0']
                ];
            }, $user['budgets']);

            $user['budgets'] = $budgets;

            // make sure that the default budget points to an existing budget
            // and change it if necessary
            $defaultBudgetExists = array_reduce($budgets, function ($carry, $budget) use ($user) {
                return $carry || $budget['value'] === $user['defaults']['budget'];
            }, false);

            if (!$defaultBudgetExists) {
                // if the default budget doesn't exist, check if there is a "nn" budget
                $nnBudgetExists = array_reduce($budgets, function ($carry, $budget) {
                    return $carry || $budget['value'] === 'nn';
                }, false);

                if ($nnBudgetExists) {
                    $user['defaults']['budget'] = 'nn';
                } else {
                    // if the "nn" budget does not exist, use the first budget
                    $user['defaults']['budget'] = $budgets[0]['value'];
                }
            }

            return $user;
        },
        function ($user) {
            return isset($user['budgets']) && count($user['budgets']) > 0 && isset($user['budgets'][0]['c']);
        });
    }
}