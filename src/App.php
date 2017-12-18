<?php

use Migrations\ChangeLieftToSupplierTitle;
use Migrations\FixMissingStatusField;
use Migrations\RemoveTitleWatchlistField;
use Migrations\ResetMissingCovers;
use Migrations\RemoveWrongCoverURLs;
use MongoDB\Client;
use Migrations\ChangeWatchlists;
use Migrations\RemoveUserPriceInfo;
use Migrations\RemovePageSizeSetting;
use Migrations\ChangeLieftToSupplier;
use Migrations\RenameBudgetsAndDefault;
use MongoDB\Driver\Exception\ConnectionTimeoutException;

/**
 * The main application class
 *
 * Class App
 */
class App {

    use Logging;

    /**
     * Runs the app.
     *
     * @param $argc int Number of arguments
     * @param $argv array Arguments
     */
    public function run($argc, $argv) {

        // Check if we got all arguments
        if ($argc !== 3) {
            fprintf(STDERR, "Usage: %s <host> <port>\n", $argv[0]);
            exit();
        }

        $host = $argv[1];
        $port = intval($argv[2]);

        self::printToLog('Parse host ' . $host . ' and port ' . $port);

        // try to feed host and port to the mongodb client to ensure
        // the correct format. No connection is established at this point.
        $client = null;
        try {
            $client = new Client('mongodb://' . $host . ':' . $port);
        } catch (InvalidArgumentException $e) {
            self::errorAndDie('Invalid Arguments: ', $e);
        }

        self::printToLog('Trying to connect to the database');

        // check if a connection is possible and if there is a database
        // for the Profildienst
        $databases = [];
        try {
            $databases = $client->listDatabases();
        } catch (ConnectionTimeoutException $e) {
            self::errorAndDie('Failed to connect to the database', $e);
        }

        self::printToLog('Connected!');
        self::printToLog('Looking for the Profildienst Database');

        $pdDatabaseFound = false;
        foreach ($databases as $database) {
            $pdDatabaseFound = $database->getName() === 'pd';
            break;
        }

        if (!$pdDatabaseFound) {
            self::errorAndDie('Profildienst database not found');
        }

        self::printToLog('Profildienst database found');

        $db = $client->selectDatabase('pd');

        $titles = $db->selectCollection('titles');
        $users = $db->selectCollection('users');

        // define here which migrations to run
        $migrations = [
           /*new RemoveUserPriceInfo($users),
            new RemovePageSizeSetting($users),
            new ChangeLieftToSupplier($users),
            /*new RenameBudgetsAndDefault($users),
            new ChangeWatchlists($users, $titles),
            new RemoveTitleWatchlistField($titles),
            new FixMissingStatusField($titles)
            new ChangeLieftToSupplierTitle($titles),
            new ResetMissingCovers($titles)*/
           new RemoveWrongCoverURLs($titles)
        ];

        // run each migration as specified above
        foreach ($migrations as $migration) {
            $migration->apply();
        }

    }

}