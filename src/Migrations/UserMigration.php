<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 28.08.16
 * Time: 14:00
 */

namespace Migrations;


use MongoDB\Collection;

abstract class UserMigration extends Migration {
    /**
     * @var Collection
     */
    private $users;

    /**
     * UserMigration constructor.
     * @param Collection $users
     */
    public function __construct(Collection $users) {

        $this->users = $users;
    }

    public function forEachUser(callable $func, callable $applicableFunc) {
        $allUsers = $this->users->find([], ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']])->toArray();
        foreach ($allUsers as $user) {

            // save the id for a possible future update
            $id = $user['_id'];

            if (call_user_func($applicableFunc, $user) !== true){
                $this->printToLog(get_class($this) . ' is not applicable to user ' . $id);
                continue;
            }

            $this->printToLog('Applying ' . get_class($this) . ' to user ' . $id);

            $ret = call_user_func($func, $user);

            if ($ret === FALSE) {
                $this->errorAndDie('An error occured!');
            }

            if (!is_null($ret)) {

                if (!isset($ret['_id'])) {
                    $this->errorAndDie('The mutator function most likely returned an unintended value! Make sure you return the user object.');
                }

                $result = $this->users->replaceOne(['_id' => $id], $ret);
                if ($result->getMatchedCount() === 1 && $result->isAcknowledged()) {
                    $this->printToLog('Saved modified user information to the database');
                } else {
                    $this->errorAndDie('An error occured while saving the modified document');
                }
            }
        }

    }
}