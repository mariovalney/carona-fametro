<?php

/**
 * Module Phpgeo
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

use \Location\Coordinate;
use \Location\Polygon;
use \Location\Polyline;
use \Location\Distance\Vincenty;

class Phpgeo {
    const KM_IN_COORD_DISTANCE = 0.00901;

    public static function calculateDistance( $coord1, $coord2 ) {
        $coord1 = self::createCoordinate( $coord1 );
        $coord2 = self::createCoordinate( $coord2 );

        if ( ! $coord1 || ! $coord2 ) {
            return 0;
        }

        return $coord1->getDistance( $coord2, new Vincenty() );
    }

    public static function createPolyline( $points ) {
        $polyline = new Polyline();

        foreach ( $points as $point ) {
            $polyline->addPoint( $point );
        }

        return $polyline;
    }

    /**
     * By Agus Prawoto Hadi
     *
     * @link http://webdevzoom.com/get-center-of-polygon-triangle-and-area-using-javascript-and-php/
     */
    public static function calculateClusterCentroid( $coords ) {
        $centroid = array_reduce( $coords, function ($x,$y) use ( $coords ) {
            $len = count( $coords );
            return [ $x[0] + $y[0] / $len, $x[1] + $y[1] / $len ];
        }, [ 0, 0 ] );

        return $centroid;
    }

    /**
     * Valid Directions:
     *
     * 0 - none
     * 1 - north
     * 2 - northwest
     * 3 - northeast
     * 4 - south
     * 5 - southeast
     * 6 - southwest
     * 7 - west
     * 8 - east
     */
    public static function calculatePolygonVerticesFromRoute( $maneuvers ) {
        $polygon = new Polygon();
        $inverseCoordinates = [];

        $maneuvers = array_values( $maneuvers );

        $perpendiculars = [];
        foreach ( $maneuvers as $key => $maneuver ) {
            if ( $key == 0 ) continue;

            $last_maneuver = $maneuvers[ $key - 1 ];

            $pointA = [ $last_maneuver->startPoint->lat, $last_maneuver->startPoint->lng ];
            $pointB = [ $maneuver->startPoint->lat, $maneuver->startPoint->lng ];

            $perpendicular = self::getPerpendicularPoints( $pointA, $pointB );
            $perpendiculars[] = [ $maneuver->direction, $perpendicular];

            $direction = ( $last_maneuver->direction >= $maneuver->direction ) ? 0 : 1;
            $inverse = ( $direction ) ? 0 : 1;

            $polygon->addPoint( self::createCoordinate( $perpendicular[ $direction ] ) );
            $inverseCoordinates[] = self::createCoordinate( $perpendicular[ $inverse ] );
        }

        $inverseCoordinates = array_reverse( $inverseCoordinates );

        foreach ( $inverseCoordinates as $coordinate ) {
            $polygon->addPoint( $coordinate );
        }

        exit;
    }

    public static function createCoordinate( $value ) {
        if ( is_object( $value ) ) {
            $value = (array) $value;
        }

        if ( ! is_array( $value ) ) {
            $value = explode( $value, ',' );
        }

        if ( count( $value ) >= 2 ) {
            if ( isset( $value['lat'] ) && isset( $value['lng'] ) ) {
                $value = [ $value['lat'], $value['lng'] ];
            }

            return new Coordinate( $value[0], $value[1] );
        }

        return false;
    }

    /**
     * Get the third coordinate of a triangle (positive and negative axis),
     * if we know the points A and B and we C is perpendicular to B.
     *
     * @link https://math.stackexchange.com/questions/3007159/third-cordinate-of-a-triangle-when-we-know-two-sides-and-two-other-points/3007187#3007187
     */
    private static function getPerpendicularPoints( $pointA, $pointB ) {
        $width = self::KM_IN_COORD_DISTANCE * 0.05;

        // A Point
        $ax = $pointA[0];
        $ay = $pointA[1];

        // B Point
        $bx = $pointB[0];
        $by = $pointB[1];

        // Slope of AB and BC
        $mab = ( $by - $ay ) / ( $bx - $ax );
        $mbc = -1 * ( 1 / $mab );

        // Angle from BC to horizontal axis
        $angle = atan( $mbc );

        // Moving from B in positive X
        $cx = $bx + $width * cos( $angle );
        $cy = $by + $width * sin( $angle );

        // Moving from B in negative X
        $cx2 = $bx - $width * cos( $angle );
        $cy2 = $by - $width * sin( $angle );

        return [ [ $cx, $cy ], [ $cx2, $cy2 ] ];
    }
}