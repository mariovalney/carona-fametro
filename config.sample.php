<?php

/**
 * Config: the configuration file.
 *
 * @package Avant
 */

/**
 * You know what to do...
 */

/** Site Config **/
define('SITE_NAME', '');
define('LANG', 'pt_BR');

/**
 * Database configs
 */

define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_HOST', '');

/** Theme config **/
define('THEME', 'app');

/** Debug config **/
define('DEBUG', false);

/** URL and PATH config **/
define('BASE_URL', '');
define('CONFIG_FILE', __FILE__);

/** Software constants **/
define('SCRIPT_VERSION', date('Ymd'));

/********************************************************************
 *
 *  That's all. Stop editing :)
 *
 ********************************************************************/

/** The directories name **/
define('CORE_DIR', 'core');
define('THEMES_DIR', 'themes');