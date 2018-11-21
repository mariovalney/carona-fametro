<?php

/**
 * API Database
 *
 * @package Avant
 */

namespace Avant\Modules;

class Database {
    const ENTITIES = [
        'user'  => 'Avant\Modules\Entities\User',
        'route' => 'Avant\Modules\Entities\Route',
    ];

    /**
     * Instance.
     *
     * Holds the plugin instance.
     */
    public static $instance = null;

    /**
     * Database
     *
     * Holds avdb.
     */
    public $db = null;

    public function __clone() { error_log( 'Cannot use __clone' ); }

    public function __wakeup() { error_log( 'Cannot use __wakeup' ); }

    /**
     * Instance.
     *
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     *
     * Initializing stuff.
     */
    private function __construct() {
        global $avdb;

        $this->db = $avdb;
    }

    public function get( $entity, $filters = [] ) {
        $classname = self::ENTITIES[ $entity ];

        $where = array();
        if ( ! empty( $filters ) ) {
            $columns = $classname::filters();
            foreach ( $filters as $column => $value ) {
                if ( ! in_array( $column, $columns ) ) continue;

                $where[ $column ] = $value;
            }
        }

        $results = $this->db->select( $this->get_table( $entity ), null, $where );

        return array_map(function( $row ) use ( $classname ) {
            return $classname::get_instance( $row );
        }, $results );
    }

    public function post( $entity, $object ) {
        return $object->save();
    }

    public function delete( $entity, $object ) {
        return $object->delete();
    }

    public function query( $query, $values = [] ) {
        return $this->db->query( $query, $values );
    }

    private function get_table( $entity ) {
        return call_user_func( self::ENTITIES[ $entity ] . '::table' );
    }
}