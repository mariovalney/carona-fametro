<?php

/**
 * Module Ride
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

use Avant\Modules\Entities\Route;
use Avant\Modules\Phpgeo;
use Avant\Modules\Database;
use Avant\Modules\Mapquest;

class Ride {

    const KMH = 30;

    private $route;

    public function __construct( Route $route ) {
        $this->route = $route;
    }

    public function getRidesFromStart() {
        return $this->getRides( 'start' );
    }

    public function getRidesFromReturn() {
        return $this->getRides( 'return' );
    }

    private function getRides( $type ) {
        $query = 'SELECT routes.*, users.displayName, users.avatar FROM routes LEFT JOIN users ON routes.userId = users.ID';
        $query .= ' WHERE (routes.' . $type . 'Time >= ? AND routes.' . $type . 'Time <= ?) AND routes.campusName IN ( %campusName% )';
        $query .= ' AND routes.dow = ? AND routes.isDriver = ?';

        // Get Start/Return coords
        $point = [ $this->route->{($type . 'Lat')}, $this->route->{($type . 'Lng')} ];

        // Time from point to campi cluster
        $time_from_cluster = $this->calculateDistanceFromCluster( $point );
        $time_from_cluster = $this->distanceToTime( $time_from_cluster );

        // Add time range array to query values
        $values = getTimeRange( $this->route->{($type . 'Time')}, $time_from_cluster );

        // Add campusName to query
        $cluster_names = $this->getClusterNameArray();

        $placeholders = str_repeat( '?,', count( $cluster_names ) );
        $placeholders = rtrim( $placeholders, ',' );

        $query = str_replace( '%campusName%', $placeholders, $query );

        $values = array_merge( $values, $cluster_names );

        // Add DOW
        $values[] = $this->route->dow;

        /**
         * Add isDriver
         * Revert because we just need one driver
         */
        $values[] = ( $this->route->isDriver ) ? '0' : '1';

        // Just saving stuff before filter by route
        $the_query = $query;
        $the_values = $values;

        /**
         * Add filtering by route
         */
        $direction_cache = 'direction-' . $this->route->ID . '-' . $type;
        $direction = get_cache( $direction_cache );

        if ( empty( $direction ) ) {
            $direction = Mapquest::getRoute( $point, $this->getClusterCenter() );
            create_cache( $direction_cache, $direction );
        }

        $sql = false;
        try {
            $sql = $this->createSQLWherePolygonFromMapquestRoute( $direction );
        } catch (Exception $e) {
            error_log( 'Not possible to get route from Maprequest' );
        }

        // Add Lats and Lngs
        if ( ! empty( $sql ) ) {
            $query .= $sql[0];

            $values = array_merge( $values, $sql[1] );

            $query = str_replace( '%lat%', 'routes.' . $type . 'Lat', $query );
            $query = str_replace( '%lng%', 'routes.' . $type . 'Lng', $query );
        }

        // Get from Database
        $query .= ' ORDER BY RAND() LIMIT 5';

        $rides = Database::instance()->query( $query, $values );
        $rides = ( ! empty( $rides ) ) ? $rides : [];

        if ( count( $rides ) >= 5 ) {
            return $rides;
        }

        $route_ids = array_map( function( $route ) {
            return $route->ID;
        }, $rides );

        if ( ! empty( $rides ) ) {
            $the_query .= ' AND routes.ID IS NOT IN (' . implode( ',', $route_ids ) . ')';
        }

        $the_query .= ' ORDER BY RAND() LIMIT ' . ( 5 - count( $rides ) );

        $other_rides = Database::instance()->query( $the_query, $the_values );
        $other_rides = ( ! empty( $other_rides ) ) ? $other_rides : [];

        return array_merge( $rides, $other_rides );
    }

    private function calculateDistanceFromCluster( $point ) {
        $clusterCenter = $this->getClusterCenter();
        return Phpgeo::calculateDistance( $point, $clusterCenter );
    }

    private function getClusterCenter() {
        if ( empty( $this->clusterCenter ) ) {
            // Get center of campi cluster
            $cluster_polygon = [];

            $cluster = Route::campi_clusters( $this->route->campusName );
            foreach ( $cluster as $campus ) {
                $cluster_polygon[] = [ $campus['lat'], $campus['lng'] ];
            }

            $this->clusterCenter = Phpgeo::calculateClusterCentroid( $cluster_polygon );
        }

        return $this->clusterCenter;
    }

    private function getClusterNameArray() {
        if ( empty( $this->clusterNameArray ) ) {
            $this->clusterNameArray = array_map( function( $campus ) {
                return $campus['name'];
            }, Route::campi_clusters( $this->route->campusName ) );
        }

        return $this->clusterNameArray;
    }

    private function createSQLWherePolygonFromMapquestRoute( $direction ) {
        if ( empty( $direction->route ) ) return false;
        if ( empty( $direction->route->legs ) ) return false;

        $maneuvers = [];
        foreach ( $direction->route->legs as $leg ) {
            if ( empty( $leg->maneuvers ) ) continue;

            $maneuvers = array_merge( $maneuvers, $leg->maneuvers );
        }

        $square = Phpgeo::calculateTheBigSquareFromManeuvers( $maneuvers );
        if ( ! is_array( $square ) || empty( $square[0] ) || empty( $square[0][1] ) ) return false;

        $sql = ' AND ( ? <= %lat% AND %lat% <= ? ) AND ( ? <= %lng% AND %lng% <= ? )';
        $values = array( $square[0][0], $square[1][0], $square[0][1], $square[1][1] );

        return [ $sql, $values ];
    }

    /**
     * Estimated time to take this distance
     *
     * We estimate a 30 km/h average and agroup round to 10 minutes groups
     */
    private function distanceToTime( $distance ) {
        return ceil( ( $distance / self::KMH * 0.06 ) / 10 ) * 10;
    }
}