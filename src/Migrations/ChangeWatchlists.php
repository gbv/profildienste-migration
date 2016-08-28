<?php
/**
 * Created by PhpStorm.
 * User: luca
 * Date: 28.08.16
 * Time: 17:54
 */

namespace Migrations;


use MongoDB\Collection;

class ChangeWatchlists extends UserMigration {
    /**
     * @var Collection
     */
    private $titles;

    /**
     * ChangeWatchlists constructor.
     * @param \MongoDB\Collection $users
     * @param \MongoDB\Collection $titles
     */
    public function __construct($users, Collection $titles) {
        parent::__construct($users);
        $this->titles = $titles;
    }

    public function apply() {
        $this->forEachUser(function ($user) {

            // get watchlists and order them according to the wl_order field
            $watchlists = [];
            foreach ($user['wl_order'] as $order) {
                $watchlists[] = $user['watchlist'][$order];
            }

            // remove the order field
            unset($user['wl_order']);

            // add default attribute to each watchlist
            $watchlists = array_map(function ($watchlist) use ($user) {
                $watchlist['default'] = $watchlist['id'] == $user['wl_default'];
                return $watchlist;
            }, $watchlists);

            // remove the old default field
            unset($user['wl_default']);


            // change the id of the watchlists to a random id
            $assignedIds = [];
            $reassignedWatchlists = [];
            foreach ($watchlists as $watchlist) {

                $oldWlId = $watchlist['id'];

                // generate a new id
                do {
                    $watchlist['id'] = uniqid();
                } while (in_array($watchlist['id'], $assignedIds));
                $assignedIds[] = $watchlist['id'];

                //update all titles accordingly
                $titleUpdateResult = $this->titles->updateMany(
                    ['$and' => [['user' => $user['_id']], ['watchlist' => $oldWlId]]],
                    ['$set' => ['watchlist' => $watchlist['id']]]
                );

                if (!$titleUpdateResult->isAcknowledged()){
                    $this->errorAndDie('Error updating the titles');
                }

                $reassignedWatchlists[] = $watchlist;
            }

            unset($user['watchlist']);

            $user['watchlists'] = $reassignedWatchlists;

            return $user;
        }, function ($user) {
            return isset($user['watchlist'], $user['wl_default'], $user['wl_order']);
        });
    }
}