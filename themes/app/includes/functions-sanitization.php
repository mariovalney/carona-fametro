<?php

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