<?php

/**
 * API Entity Invite
 *
 * @package Avant
 */

namespace Avant\Modules\Entities;

class Invite {

    public $ID;

    public $routeId = '';

    public $rideId = '';

    public $route = '';

    public $ride = '';

    public $type = '';

    public $sendedMails = 0;

    public $isAccepted = 0;

    public $created_at = '';

    /**
     * Retrieve instance.
     *
     */
    public static function get_instance( $invite = false ) {
        global $avdb;

        if ( is_object( $invite ) ) {
            $invite = \sanitize_invite( $invite );
            return new Invite( $invite );
        }

        if ( ! $invite ) return false;

        $data = $avdb->select( self::table(), null, [ 'ID' => $invite ] );
        if ( empty( $data ) || empty( $data[0] ) ) return false;

        $data = \sanitize_invite( $data[0] );

        return new Invite( $data );
    }

    public function __construct( $object ) {
        foreach ( get_object_vars( $object ) as $key => $value ) {
            $this->$key = $value;
        }
    }

    public function addStaticRoutesData() {
        $this->route = get_route_by( 'ID', $this->routeId );
        $this->route = ( $this->route ) ? json_encode( $this->route ) : '';

        $this->ride = get_route_by( 'ID', $this->rideId );
        $this->ride = ( $this->ride ) ? json_encode( $this->ride ) : '';
    }

    public function save() {
        global $avdb;

        $fields = array(
            'type',
            'sendedMails',
            'isAccepted',
        );

        $values = array(
            $this->type,
            $this->sendedMails,
            $this->isAccepted,
        );

        // Only if it's not in database yet
        if ( empty( $this->ID ) ) {
            $this->addStaticRoutesData();

            $fields = array(
                'routeId',
                'rideId',
                'route',
                'ride',
                'type',
                'sendedMails',
                'isAccepted',
            );

            $values = array(
                $this->routeId,
                $this->rideId,
                $this->route,
                $this->ride,
                $this->type,
                $this->sendedMails,
                $this->isAccepted,
            );
        }

        if ( empty( $this->type ) || ! in_array( $this->type, [ 'start', 'return' ] ) ) {
            return false;
        }

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
        return \sanitize_invite( $this );
    }

    public function to_array() {
        return get_object_vars( $this );
    }

    public function to_json() {
        return json_encode( $this );
    }

    public static function table() {
        return 'invites';
    }

    public static function filters() {
        return [ 'ID', 'routeId', 'rideId' ];
    }
}