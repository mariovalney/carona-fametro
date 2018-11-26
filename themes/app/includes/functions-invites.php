<?php

function sanitize_invite( $data ) {
    if ( is_object( $data ) ) {
        return $data;
    }

    return $data;
}

function get_invite_by( $field, $value ) {
    $invites = get_invites_by( $field, $value );
    return $invites[0] ?? false;
}

function get_invites_by( $field, $value ) {
    $db = \Avant\Modules\Database::instance();
    $result = $db->get( 'invite', [ $field => $value ] );

    return $result ?? [];
}

function get_invites() {
    $db = \Avant\Modules\Database::instance();
    $result = $db->get( 'invite' );

    return $result ?? [];
}

function delete_invites_by( $field, $value ) {
    $invites = get_invites_by( $field, $value );

    foreach ( $invites as $invite ) {
        $invite->delete();
    }
}