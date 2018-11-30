<?php

function create_cache( $key, $value, $single = true ) {
    global $avdb;

    if ( $single ) {
        $avdb->delete( 'cache', [ 'cache_key' => $key ] );
    }

    return $avdb->insert( 'cache', [ 'cache_key', 'cache_value' ], [ $key, serialize( $value ) ] );
}

function get_cache( $key, $single = true ) {
    global $avdb;

    $values = $avdb->select( 'cache', null, [ 'cache_key' => $key ] );

    if ( empty( $values ) ) return [];

    return array_map( function( $result ) {
        return unserialize( $result->cache_value );
    }, $values );
}

function delete_cache( $key ) {
    global $avdb;

    return $avdb->delete( 'cache', [ 'cache_key' => $key ] );
}