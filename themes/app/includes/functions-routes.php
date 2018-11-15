<?php

function sanitize_route( $data ) {
    if ( is_object( $data ) ) {
        return $data;
    }

    return $data;
}

function get_route_by( $field, $value ) {
    $routes = get_routes_by( $field, $value );
    return $routes[0] ?? false;
}

function get_routes_by( $field, $value ) {
    $db = \Avant\Modules\Database::instance();
    $result = $db->get( 'route', [ $field => $value ] );

    return $result ?? [];
}

function get_default_campus() {
    $default_campus = \Avant\Modules\Entities\Route::valid_campi();
    return $default_campus[0]['name'] ?? '';
}