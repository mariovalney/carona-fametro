<?php

/**
 * Index: receive all the requests and do the Magic...
 *
 * @package Avant
 */

/** Define the Directory Separator (DS) **/
define('DS', DIRECTORY_SEPARATOR);

/** Define the ROOT **/
define('ROOT', dirname(__FILE__) . DS);

/** Turn on all the errors: it's nice to developers **/
if ( DEBUG ) {
    error_reporting( E_ALL );
    ini_set( 'display_errors', 0 );
    ini_set( 'log_errors', 1 );
    ini_set( 'error_log', ROOT . 'debug.log' );
} else {
    error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
}

/** Reading the configurations **/
if (file_exists(ROOT . 'config.php')) {
    require_once(ROOT . 'config.php');
} else {
    // Die with an error message
    die("I can't find the <code>config.php</code> file. <br> Please, check the file or create a new one.");
}

/** Loading Core **/
require_once(ROOT . CORE_DIR . DS . 'loader.php');