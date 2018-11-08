<?php

if ( ! defined( 'VZR_TEXTDOMAIN' ) ) {
    define( 'VZR_TEXTDOMAIN', 'carona-fametro' );
}

function get_theme_image( $file, $echo = true, $add_version = false ) {
    theme_file_url( 'dist/images/' . $file, $echo, $add_version );
}

function sanitize_text_field( $string ) {
    $string = sanitize_textarea_field( $string );
    $string = preg_replace( '/[\r\n\t ]+/', ' ', $string );

    return trim( $string );
}

function sanitize_textarea_field( $string ) {
    $string = filter_var( $string, FILTER_SANITIZE_STRING );
    return trim( $string );
}

function sanitize_mail_field( $string ) {
    return filter_var( $string, FILTER_SANITIZE_EMAIL );
}