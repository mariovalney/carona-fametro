<?php

/**
 * API Entity Route
 *
 * @package Avant
 */

namespace Avant\Modules\Entities;

class Route {

    public $ID;

    public $userId = '';

    public $startLat = '';

    public $startLng = '';

    public $returnLat = '';

    public $returnLng = '';

    public $startPlace = '';

    public $returnPlace = '';

    public $startTime = '';

    public $returnTime = '';

    public $campusName = '';

    public $isDriver = 0;

    public $dow = 0;

    public $created_at = '';

    /**
     * Retrieve instance.
     *
     */
    public static function get_instance( $route = false ) {
        global $avdb;

        if ( is_object( $route ) ) {
            $route = \sanitize_route( $route );
            return new Route( $route );
        }

        if ( ! $route ) return false;

        $data = $avdb->select( self::table(), null, [ 'ID' => $route ] );
        if ( empty( $data ) || empty( $data[0] ) ) return false;

        $data = \sanitize_route( $data[0] );

        return new Route( $data );
    }

    public function __construct( $object ) {
        foreach ( get_object_vars( $object ) as $key => $value ) {
            $this->$key = $value;
        }
    }

    public function save() {
        global $avdb;

        $fields = array(
            'userId',
            'startLat',
            'startLng',
            'returnLat',
            'returnLng',
            'startTime',
            'returnTime',
            'startPlace',
            'returnPlace',
            'campusName',
            'isDriver',
            'dow',
        );

        $values = array(
            $this->userId,
            $this->startLat,
            $this->startLng,
            $this->returnLat,
            $this->returnLng,
            $this->startTime,
            $this->returnTime,
            $this->startPlace,
            $this->returnPlace,
            $this->campusName,
            $this->isDriver,
            $this->dow,
        );

        if ( empty( $this->createdAt ) ) {
            $this->createdAt = date('Y-m-d H:i:s');
        }

        if ( ! empty( $this->ID ) ) {
            return $avdb->update( self::table(), $fields, $values, 'ID', $this->ID );
        }

        $result = $avdb->insert( self::table(), $fields, $values );
        if ( ! empty( $result ) ) {
            $this->ID = $result;
        }

        return $this->ID;
    }

    public function delete() {
        global $avdb;

        if ( empty( $this->ID ) ) return true;
        return $avdb->delete( self::table(), [ 'ID' => $this->ID ] );
    }

    public function sanitize() {
        return \sanitize_route( $this );
    }

    public function to_array() {
        return get_object_vars( $this );
    }

    public function to_json() {
        return json_encode( $this );
    }

    public static function table() {
        return 'routes';
    }

    public static function filters() {
        return [ 'ID', 'userId', 'campusName', 'isDriver', 'dow' ];
    }

    public static function valid_campi() {
        return array(
            array(
                'name'  => 'Conselheiro Estelita',
                'lat'   => '-3.725420',
                'lng'   => '-38.539780',
            ),
            array(
                'name'  => 'Padre Ibiapina',
                'lat'   => '-3.726195',
                'lng'   => '-38.540271',
            ),
            array(
                'name'  => 'Guilherme Rocha',
                'lat'   => '-3.723146',
                'lng'   => '-38.539291',
            ),
            array(
                'name'  => 'Carneiro da Cunha',
                'lat'   => '-3.724029',
                'lng'   => '-38.543084',
            ),
            array(
                'name'  => 'Maracanaú',
                'lat'   => '-3.829430',
                'lng'   => '-38.625519',
            ),
        );
    }

    public static function is_valid_campus( $campus_name ) {
        $campi = self::valid_campi();

        foreach ( $campi as $campus ) {
            if ( $campus['name'] == $campus_name ) {
                return true;
            }
        }

        return false;
    }
}