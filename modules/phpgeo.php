<?php

/**
 * Module Phpgeo
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

use \Location\Coordinate;
use \Location\Polyline;
use \Location\Distance\Vincenty;

class Phpgeo {
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

    public static function calculatePolygonVerticesFromLine( $coords ) {
        $width = 10;

        // Only the first line:

        // A Point
        $a = $coords[0];
        $ax = $a[0];
        $ay = $a[1];

        // B Point
        $b = $coords[1];
        $bx = $b[0];
        $by = $b[1];
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
}