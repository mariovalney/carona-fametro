<?php

global $avdb;

if ( ! defined( 'VZR_TEXTDOMAIN' ) ) {
    define( 'VZR_TEXTDOMAIN', 'carona-fametro' );
}

function get_theme_image( $file, $echo = true, $add_version = false ) {
    theme_file_url( 'dist/images/' . $file, $echo, $add_version );
}

// Clear Cache every 12 hours
$limit = date( 'Y-m-d H:i', strtotime('-12 hours') );
$avdb->query( "DELETE FROM cache WHERE created_at < ?", $limit );

include ROOT . THEMES_DIR . '/app/includes/functions-misc.php';
include ROOT . THEMES_DIR . '/app/includes/functions-datetime.php';
include ROOT . THEMES_DIR . '/app/includes/functions-users.php';
include ROOT . THEMES_DIR . '/app/includes/functions-routes.php';
include ROOT . THEMES_DIR . '/app/includes/functions-invites.php';
include ROOT . THEMES_DIR . '/app/includes/functions-cache.php';
include ROOT . THEMES_DIR . '/app/includes/functions-sanitization.php';