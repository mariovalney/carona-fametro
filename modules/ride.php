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
        $query = 'SELECT routes.*, users.firstName FROM routes LEFT JOIN users ON routes.userId = users.ID';
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

        // Just for now...
        $query .= ' ORDER BY RAND() LIMIT 5';
        return Database::instance()->query( $query, $values );

        /**
         * Add range limit
         */
        // $direction = Mapquest::getRoute( $point, $this->getClusterCenter() );
        $direction = '{"route":{"hasTollRoad":false,"hasBridge":false,"boundingBox":{"lr":{"lng":-38.540634,"lat":-3.740207},"ul":{"lng":-38.594646,"lat":-3.724687}},"distance":7.2243,"hasTimedRestriction":false,"hasTunnel":false,"hasHighway":false,"computedWaypoints":[],"routeError":{"errorCode":-400,"message":""},"formattedTime":"00:09:31","sessionId":"5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","hasAccessRestriction":false,"realTime":-1,"hasSeasonalClosure":false,"hasCountryCross":false,"fuelUsed":0.24,"legs":[{"hasTollRoad":false,"hasBridge":false,"destNarrative":"Proceed to your destination.","distance":7.2243,"hasTimedRestriction":false,"hasTunnel":false,"hasHighway":false,"index":0,"formattedTime":"00:09:31","origIndex":2,"hasAccessRestriction":false,"hasSeasonalClosure":false,"hasCountryCross":false,"roadGradeStrategy":[[]],"destIndex":6,"time":571,"hasUnpaved":false,"origNarrative":"Go east on Avenida Mister Hull.","maneuvers":[{"distance":0.177,"streets":["Avenida Mister Hull"],"narrative":"","turnType":0,"startPoint":{"lng":-38.593071,"lat":-3.739714},"index":0,"formattedTime":"00:00:16","directionName":"West","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.7397141456604004,-38.59307098388672|marker-1||-3.7399420738220215,-38.59464645385742|marker-2||&center=-3.739828109741211,-38.59385871887207&defaultMarker=none&zoom=15&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":16,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/icon-dirs-start_sm.gif","direction":7},{"distance":0.029,"streets":["Rua Padre Perdig\u00e3o Sampaio"],"narrative":"","turnType":6,"startPoint":{"lng":-38.594646,"lat":-3.739942},"index":1,"formattedTime":"00:00:13","directionName":"South","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.7399420738220215,-38.59464645385742|marker-2||-3.7402069568634033,-38.59462356567383|marker-3||&center=-3.7400745153427124,-38.594635009765625&defaultMarker=none&zoom=15&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":13,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_left_sm.gif","direction":4},{"distance":2.8244,"streets":["Avenida Mister Hull"],"narrative":"","turnType":6,"startPoint":{"lng":-38.594624,"lat":-3.740207},"index":2,"formattedTime":"00:02:19","directionName":"East","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.7402069568634033,-38.59462356567383|marker-3||-3.736769914627075,-38.56943893432617|marker-4||&center=-3.7384884357452393,-38.58203125&defaultMarker=none&zoom=10&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":139,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_left_sm.gif","direction":8},{"distance":2.0873,"streets":["Avenida Bezerra de Menezes"],"narrative":"","turnType":7,"startPoint":{"lng":-38.569439,"lat":-3.73677},"index":3,"formattedTime":"00:02:47","directionName":"East","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.736769914627075,-38.56943893432617|marker-4||-3.7329649925231934,-38.55104446411133|marker-5||&center=-3.7348674535751343,-38.56024169921875&defaultMarker=none&zoom=10&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":167,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_slight_left_sm.gif","direction":8},{"distance":0.0177,"streets":["Rua Jos\u00e9 de Barcelos"],"narrative":"","turnType":6,"startPoint":{"lng":-38.551044,"lat":-3.732965},"index":4,"formattedTime":"00:00:13","directionName":"North","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.7329649925231934,-38.55104446411133|marker-5||-3.7328031063079834,-38.55107116699219|marker-6||&center=-3.7328840494155884,-38.55105781555176&defaultMarker=none&zoom=15&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":13,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_left_sm.gif","direction":1},{"distance":0.0467,"streets":["Avenida Bezerra de Menezes"],"narrative":"","turnType":6,"startPoint":{"lng":-38.551071,"lat":-3.732803},"index":5,"formattedTime":"00:00:06","directionName":"West","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.7328031063079834,-38.55107116699219|marker-6||-3.732882022857666,-38.55147933959961|marker-7||&center=-3.7328425645828247,-38.5512752532959&defaultMarker=none&zoom=15&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":6,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_left_sm.gif","direction":7},{"distance":0.9157,"streets":["Rua Jos\u00e9 C\u00e2ndido"],"narrative":"","turnType":2,"startPoint":{"lng":-38.551479,"lat":-3.732882},"index":6,"formattedTime":"00:00:57","directionName":"North","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.732882022857666,-38.55147933959961|marker-7||-3.7247281074523926,-38.55044937133789|marker-8||&center=-3.7288050651550293,-38.55096435546875&defaultMarker=none&zoom=10&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":57,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_right_sm.gif","direction":1},{"distance":0.5086,"streets":["Avenida Sargento Herm\u00ednio Sampaio"],"narrative":"","turnType":2,"startPoint":{"lng":-38.550449,"lat":-3.724728},"index":7,"formattedTime":"00:00:55","directionName":"East","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.7247281074523926,-38.55044937133789|marker-8||-3.724687099456787,-38.5458984375|marker-9||&center=-3.72470760345459,-38.548173904418945&defaultMarker=none&zoom=12&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":55,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_right_sm.gif","direction":8},{"distance":0.5906,"streets":["Rua Carneiro da Cunha"],"narrative":"","turnType":0,"startPoint":{"lng":-38.545898,"lat":-3.724687},"index":8,"formattedTime":"00:01:42","directionName":"East","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.724687099456787,-38.5458984375|marker-9||-3.724925994873047,-38.54069900512695|marker-10||&center=-3.724806547164917,-38.54329872131348&defaultMarker=none&zoom=12&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":102,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_straight_sm.gif","direction":8},{"distance":0.0274,"streets":["Rua Oto de Alencar"],"narrative":"","turnType":6,"startPoint":{"lng":-38.540699,"lat":-3.724926},"index":9,"formattedTime":"00:00:03","directionName":"North","maneuverNotes":[],"linkIds":[],"signs":[],"mapUrl":"http:\/\/open.mapquestapi.com\/staticmap\/v5\/map?key=S1JjSXQTZqIvYScrY5ALG9BUCAJVc3MA&size=225,160&locations=-3.724925994873047,-38.54069900512695|marker-10||-3.7246901988983154,-38.54063415527344|marker-11||&center=-3.724808096885681,-38.540666580200195&defaultMarker=none&zoom=15&rand=1844364207&session=5bf49a2a-01ad-4ee4-02b4-236b-0af5f91f9cfa","transportMode":"AUTO","attributes":0,"time":3,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/rs_left_sm.gif","direction":1},{"distance":0,"streets":[],"narrative":"","turnType":-1,"startPoint":{"lng":-38.540634,"lat":-3.72469},"index":10,"formattedTime":"00:00:00","directionName":"","maneuverNotes":[],"linkIds":[],"signs":[],"transportMode":"AUTO","attributes":0,"time":0,"iconUrl":"http:\/\/content.mqcdn.com\/mqsite\/turnsigns\/icon-dirs-end_sm.gif","direction":0}],"hasFerry":false}],"options":{"arteryWeights":[],"cyclingRoadFactor":1,"timeType":0,"useTraffic":false,"returnLinkDirections":false,"countryBoundaryDisplay":true,"enhancedNarrative":false,"locale":"en_US","tryAvoidLinkIds":[],"drivingStyle":2,"doReverseGeocode":false,"generalize":-1,"mustAvoidLinkIds":[],"sideOfStreetDisplay":true,"routeType":"FASTEST","avoidTimedConditions":false,"routeNumber":0,"shapeFormat":"raw","maxWalkingDistance":-0.621371,"destinationManeuverDisplay":true,"transferPenalty":-1,"narrativeType":"none","walkingSpeed":-0.621371,"urbanAvoidFactor":-1,"stateBoundaryDisplay":true,"unit":"K","highwayEfficiency":22,"maxLinkId":0,"maneuverPenalty":-1,"avoidTripIds":[],"filterZoneFactor":-1,"manmaps":"true"},"locations":[{"dragPoint":false,"displayLatLng":{"lng":-38.593072,"lat":-3.739708},"adminArea4":"","adminArea5":"","postalCode":"","adminArea1":"","adminArea3":"","type":"s","sideOfStreet":"N","geocodeQualityCode":"XXXXX","adminArea4Type":"County","linkId":56017134,"street":"","adminArea5Type":"City","geocodeQuality":"LATLNG","adminArea1Type":"Country","adminArea3Type":"State","latLng":{"lng":-38.593072,"lat":-3.739708}},{"dragPoint":false,"displayLatLng":{"lng":-38.540607,"lat":-3.724697},"adminArea4":"","adminArea5":"","postalCode":"","adminArea1":"","adminArea3":"","type":"s","sideOfStreet":"N","geocodeQualityCode":"XXXXX","adminArea4Type":"County","linkId":23669950,"street":"","adminArea5Type":"City","geocodeQuality":"LATLNG","adminArea1Type":"Country","adminArea3Type":"State","latLng":{"lng":-38.540607,"lat":-3.724697}}],"time":571,"hasUnpaved":false,"locationSequence":[0,1],"hasFerry":false},"info":{"statuscode":0,"copyright":{"imageAltText":"\u00a9 2018 MapQuest, Inc.","imageUrl":"http:\/\/api.mqcdn.com\/res\/mqlogo.gif","text":"\u00a9 2018 MapQuest, Inc."},"messages":[]}}';
        $direction = json_decode( $direction );

        $polygon = $this->createPolygonFromMapquestRoute( $direction );

        exit;

        // Get from Database
        $query .= ' LIMIT 5';
        $rides = Database::instance()->query( $query, $values );

        return $rides;
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

    private function createPolygonFromMapquestRoute( $direction ) {
        if ( empty( $direction->route ) ) return false;
        if ( empty( $direction->route->legs ) ) return false;

        $maneuvers = [];
        foreach ( $direction->route->legs as $leg ) {
            if ( empty( $leg->maneuvers ) ) continue;

            $maneuvers = array_merge( $maneuvers, $leg->maneuvers );
        }

        $polygon = Phpgeo::calculatePolygonVerticesFromRoute( $maneuvers );
        print_r( $polygon );

        exit;
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