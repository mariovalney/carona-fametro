<?php

if ( ! defined( 'VZR_TEXTDOMAIN' ) ) {
    define( 'VZR_TEXTDOMAIN', 'carona-fametro' );
}

function get_theme_image( $file, $echo = true, $add_version = false ) {
    theme_file_url( 'dist/images/' . $file, $echo, $add_version );
}