<?php

/**
 * Module Mapquest
 *
 * @package Avant\Modules
 */

namespace Avant\Modules;

class Mapquest {
    public static function getRoute( $from, $to ) {
        $url = 'http://open.mapquestapi.com/directions/v2/route?&unit=k&narrativeType=none&doReverseGeocode=false';
        $url .= '&from=' . self::createLatLngRaw( $from );
        $url .= '&to=' . self::createLatLngRaw( $to );

        return self::request( $url );
    }

    public static function getPathFromRoute( $coords, $sessionId ) {
        $points = [];

        foreach ( $coords as $coord ) {
            $points[] = self::createLatLngRaw( $to, 5 );
        }

        $points = implode( ',', $points );

        $url = 'http://open.mapquestapi.com/directions/v2/pathfromroute?&unit=k';
        $url .= '&sessionId=' . $sessionId;
        $url .= '&maxTime=' . 5; // 5 minutes
        $url .= '&points=' . $points;

        return self::request( $url );
    }

    public static function request( $url ) {
        $url .= '&key=' . MAPQUEST_API;

        $curl = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );

        $results = curl_exec( $curl );

        curl_close( $curl );

        return json_decode( $results );
    }

    public static function createLatLngRaw( $coord, $precision = false ) {
        if ( ! is_array( $coord ) ) {
            $coord = explode( $coord, ',' );
        }

        if ( count( $coord ) == 2 ) {
            if ( ! $precision ) {
                return $coord[0] . ',' . $coord[1];
            }

            return round( $coord[0] , 5 ) . ',' . round( $coord[1] , 5 );
        }

        return false;
    }
}