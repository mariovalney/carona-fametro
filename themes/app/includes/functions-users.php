<?php

function sanitize_user( $data ) {
    if ( is_object( $data ) ) {
        return $data;
    }

    return $data;
}

function create_google_section( $redirect = true ) {
    return Avant\Modules\Google::create_section( $redirect );
}

function get_logged_user() {
    $user = Avant\Modules\User::getInstance();
    return $user->getLoggedUser();
}

function get_user_by( $field, $value ) {
    $db = \Avant\Modules\Database::instance();
    $result = $db->get( 'user', [ $field => $value ] );

    return $result[0] ?? [];
}

function create_user( $googleId, $data = [] ) {
    $data = (object) array(
        'googleId'      => $googleId,
        'bio'           => $data['bio'] ?? '',
        'email'         => $data['email'] ?? '',
        'firstName'     => $data['firstName'] ?? '',
        'lastName'      => $data['lastName'] ?? '',
        'displayName'   => $data['displayName'] ?? '',
        'avatar'        => $data['avatar'] ?? '',
    );

    $user = Avant\Modules\Entities\User::get_instance( $data );
    return $user->save();
}

function get_user_avatar( $user ) {
    $user = Avant\Modules\Entities\User::get_instance( $user );

    return ( ! empty( $user->avatar ) ) ? $user->avatar : get_theme_image( 'user_placeholder.jpg' );
}