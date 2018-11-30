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

function get_routes() {
    $db = \Avant\Modules\Database::instance();
    $result = $db->get( 'route' );

    return $result ?? [];
}

function delete_routes_by( $field, $value ) {
    $routes = get_routes_by( $field, $value );

    foreach ( $routes as $route ) {
        $route->delete();
    }
}

function get_default_campus() {
    $default_campus = \Avant\Modules\Entities\Route::valid_campi();
    return $default_campus[0]['name'] ?? '';
}

function get_campus_for_options() {
    $default_campus = \Avant\Modules\Entities\Route::valid_campi();
    return array_map( function( $campus ) {
        return $campus['name'];
    }, $default_campus );
}

function clear_route_cache( $routeId ) {
    $key = \Avant\Modules\Ride::createDirectionCacheKey( $routeId, 'start' );
    delete_cache( $key );

    $key = \Avant\Modules\Ride::createDirectionCacheKey( $routeId, 'return' );
    delete_cache( $key );
}