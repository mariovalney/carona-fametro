<?php

if ( ! defined( 'VZR_TEXTDOMAIN' ) ) {
    define( 'VZR_TEXTDOMAIN', 'carona-fametro' );
}

function get_theme_image( $file, $echo = true, $add_version = false ) {
    theme_file_url( 'dist/images/' . $file, $echo, $add_version );
}

include ROOT . THEMES_DIR . '/app/includes/functions-misc.php';
include ROOT . THEMES_DIR . '/app/includes/functions-datetime.php';
include ROOT . THEMES_DIR . '/app/includes/functions-users.php';
include ROOT . THEMES_DIR . '/app/includes/functions-routes.php';
include ROOT . THEMES_DIR . '/app/includes/functions-invites.php';
include ROOT . THEMES_DIR . '/app/includes/functions-sanitization.php';