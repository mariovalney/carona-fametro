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

    public $endLat = '';

    public $endLng = '';

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
    public static function get_instance( $poll = false ) {
        global $avdb;

        if ( is_object( $poll ) ) {
            $poll = \sanitize_route( $poll );
            return new Route( $poll );
        }

        if ( ! $poll ) return false;

        $data = $avdb->select( self::table(), null, [ 'ID' => $poll ] );
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
            'endLat',
            'endLng',
            'startTime',
            'returnTime',
            'campusName',
            'isDriver',
            'dow',
        );

        $values = array(
            $this->userId,
            $this->startLat,
            $this->startLng,
            $this->endLat,
            $this->endLng,
            $this->startTime,
            $this->returnTime,
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

        return $avdb->insert( self::table(), $fields, $values );
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
}