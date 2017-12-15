<?php

namespace Migrations\Common;


use MongoDB\Collection;

/**
 * This class can be extended by migrations targeting a
 * collection.
 *
 * Class CollectionMigration
 * @package Migrations
 */
abstract class CollectionMigration extends Migration {

    /**
     * @var Collection The collection
     */
    private $collection;

    /**
     * CollectionMigration constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection) {

        $this->collection = $collection;
    }

    /**
     * Iterates over every user in the users collection
     * and applies $func to each applicable element.
     *
     * @param callable $func This function is called for every user which qualifies.
     * It receives a single argument which is the user data as an array. The function may modify
     * the user data as desired. If the modified data is returned, it will be upserted into the database.
     * If the function does not return anything or null, nothing will be written back to the database.
     *
     * @param callable $applicableFunc This function determines which users are qualified for the migration.
     * It also receives a single argument (the user data as an array). The function decides if the migration
     * (i.e. $func) should be applied to the user. Therefore $applicableFunc has to return a boolean value.
     */
    public function forEachElement(callable $func, callable $applicableFunc) {

        // find and iterate through all users, return plain arrays instead of BSON Documents
        $allElements = $this->collection->find([], ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]);
        foreach ($allElements as $element) {

            // save the id for a possible future update
            $id = $element['_id'];

            // check if the mutation should be applied to the user
            if (call_user_func($applicableFunc, $element) !== true){
                $this->printToLog(get_class($this) . ' is not applicable to element ' . $id);
                continue;
            }

            $this->printToLog('Applying ' . get_class($this) . ' to element ' . $id);

            // apply the mutation (parameter $func)
            $ret = call_user_func($func, $element);

            if ($ret === FALSE) {
                $this->errorAndDie('An error occurred!');
            }

            // a return value which does not equals null means that the modified data should
            // be updated in the database
            if (!is_null($ret)) {

                if (!isset($ret['_id'])) {
                    $this->errorAndDie('The mutator function most likely returned an unintended value! Make sure you return the user object.');
                }

                $result = $this->collection->replaceOne(['_id' => $id], $ret);
                if ($result->getMatchedCount() === 1 && $result->isAcknowledged()) {
                    $this->printToLog('Saved modified element to the database');
                } else {
                    $this->errorAndDie('An error occurred while saving the modified document');
                }
            }
        }

    }
}