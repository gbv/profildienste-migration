<?php

/**
 * Created by PhpStorm.
 * User: luca
 * Date: 28.08.16
 * Time: 13:47
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