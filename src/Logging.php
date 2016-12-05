<?php

/**
 * A simple logging helper for writing info and error messages
 * to STDOUT or STDERR respectively.
 *
 * Class Logging
 */
trait Logging {

    /**
     * Writes the error message to the standard error output
     * and terminates the execution of the script immediatly.
     *
     * @param $error string The error message
     * @param Exception $e
     */
    function errorAndDie(string $error, Exception $e = null){
        $errorMessage = !is_null($e) ? ': '.$e->getMessage() : '';
        fprintf(STDERR, "<!> [%s]: %s %s \n", date('H:i:s'), $error, $errorMessage);
        exit();
    }

    /**
     * Logs a message to the standard output.
     *
     * @param string $message
     */
    function printToLog(string $message){
        fprintf(STDOUT, "[%s]: %s \n", date('H:i:s') ,$message);
    }
}