<?php

/**
 * API Entity User
 *
 * @package Avant
 */

namespace Avant\Modules\Entities;

class User {

    public $ID;

    public $googleId = '';

    public $email = '';

    public $firstName = '';

    public $lastName = '';

    public $displayName = '';

    public $avatar = '';

    public $bio = '';

    public $phone = '';

    /**
     * Retrieve instance.
     *
     */
    public static function get_instance( $user = false ) {
        global $avdb;

        if ( is_object( $user ) ) {
            $user = \sanitize_user( $user );
            return new User( $user );
        }

        if ( ! $user ) return false;

        $data = $avdb->select( self::table(), null, [ 'ID' => $user ] );
        if ( empty( $data ) || empty( $data[0] ) ) return false;

        $data = \sanitize_user( $data[0] );

        return new User( $data );
    }

    public function __construct( $object ) {
        foreach ( get_object_vars( $object ) as $key => $value ) {
            $this->$key = $value;
        }
    }

    public function save() {
        global $avdb;

        $fields = array(
            'googleId',
            'email',
            'firstName',
            'lastName',
            'displayName',
            'avatar',
            'bio',
            'phone',
        );

        $values = array(
            $this->googleId,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->displayName,
            $this->avatar,
            $this->bio,
            $this->phone,
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
        return \sanitize_user( $this );
    }

    public function to_array() {
        return get_object_vars( $this );
    }

    public function to_json() {
        return json_encode( $this );
    }

    public static function table() {
        return 'users';
    }

    public static function filters() {
        return [ 'ID', 'googleId', 'email' ];
    }
}