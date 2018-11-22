var route_maps = {},
    default_pos = { lat: -3.725420, lng: -38.539780 },
    centeredZoom = 15;

function mapsRoutes() {
    for (var i = 0; i < 7; i++) {
        $('#modal-route-' + i).on('shown.bs.modal', function(event) {
            var modal = $(this),
                dow = modal.data('dow'),
                map = createRouteMap(dow),
                startMarker = false,
                returnMarker = false;

            if ( ! map ) return;

            // Campi Markers
            var campiMarkers = addCampiMarkers(map),
                campiInput = modal.find('select[name="campus-name"]');

            campiInput.on('change', function(event) {
                var campusName = $(this).val();

                _.each(campiMarkers, function(element, index, list) {
                    var visible = element.name == campusName;

                    element.marker.setVisible(visible);

                    if (!visible) return;
                    map.setCenter(element.marker.getPosition());
                });
            });

            campiInput.trigger('change');

            // Start and Return Markers
            var startLat = modal.find('input[name="start-lat"]').val() || false,
                startLng = modal.find('input[name="start-lng"]').val() || false,
                returnLat = modal.find('input[name="return-lat"]').val() || false,
                returnLng = modal.find('input[name="return-lng"]').val() || false;

            if ( startLat !== false && startLng !== false) {
                startMarker = addStartMarker(startLat, startLng, map, false, modal);
            }

            if ( returnLat !== false && returnLng !== false) {
                returnMarker = addReturnMarker(returnLat, returnLng, map, false, modal);
            }

            var inputStartPlace = modal.find('input[name="start-place"]'),
                inputReturnPlace = modal.find('input[name="return-place"]');

            // Inicial Content
            if ( ! inputStartPlace.val() || ! inputReturnPlace.val() ) {
                getUserLocation(function(pos) {
                    getAddressFromPosition(pos, function(results) {
                        var result = results[0],
                            address = results[0].formatted_address || '';

                        if ( ! inputStartPlace.val() ) {
                            inputStartPlace.val(address);
                            modal.find('input[name="start-lat"]').val(pos.lat);
                            modal.find('input[name="start-lng"]').val(pos.lng);
                        }

                        if ( ! inputReturnPlace.val() ) {
                            inputReturnPlace.val(address);
                            modal.find('input[name="return-lat"]').val(pos.lat);
                            modal.find('input[name="return-lng"]').val(pos.lng);
                        }
                    });
                });
            }

            // On Searching Place
            modal.find('.find-marker').on('click', function(event) {
                event.preventDefault();

                var type = $(this).data('input-type');

                if ( ! type ) return;

                var input = modal.find('input[name="' + type + '-place"]');

                if ( ! input.val() ) {
                    swal( 'Você precisa digitar algum endereço para procurar', '', 'error' );
                }

                getPositionFromAddress(input.val(), function(results) {
                    var result = results[0],
                        position = results[0].geometry || false;

                    if ( ! position ) {
                        swal( 'Ops...', 'Não conseguimos achar seu endereço. Tente novamente', 'error' );
                        return;
                    }

                    var resultLat = position.location.lat(),
                        resultLng = position.location.lng();

                    modal.find('input[name="' + type + '-lat"]').val( resultLat );
                    modal.find('input[name="' + type + '-lng"]').val( resultLng );

                    swal( 'Encontrado', 'Você pode arrastar o marcador para melhorar sua posição.', 'success' );

                    if (type === 'start') {
                        if (startMarker) {
                            changeMarkerPosition( startMarker, resultLat, resultLng, map );
                            return;
                        }

                        startMarker = addStartMarker(resultLat, resultLng, map, true, modal);
                        return;
                    }

                    if (type === 'return') {
                        if (returnMarker) {
                            changeMarkerPosition( returnMarker, resultLat, resultLng, map );
                            return;
                        }

                        returnMarker = addReturnMarker(resultLat, resultLng, map, true, modal);
                        return;
                    }
                });
            });
        });

        // Of Search on Close Modal
        $('#modal-route-' + i).on('hidden.bs.modal', function(event) {
            $(this).find('.find-marker').off();
        });
    }
}

function createRouteMap(dow) {
    var mapElement = document.getElementById( 'map-routes-' + dow );

    if ( ! mapElement ) {
        route_maps[dow] = false;
        return false;
    }

    var map = new google.maps.Map(mapElement, {
        center: default_pos,
        placeInput: false,
        zoom: 13
    });

    route_maps[dow] = map;
    return map;
}

function createRideMap() {
    var mapElement = document.getElementById( 'map-rides' );

    return new google.maps.Map(mapElement, {
        center: default_pos,
        placeInput: false,
        zoom: 13
    });
}

function addNumeredMarker( num, lat, lng, map ) {
    return new google.maps.Marker({
        position: { lat: parseFloat( lat ), lng: parseFloat( lng ) },
        title: num.toString(),
        icon: ( num == 1 ) ? CF.markers.one : CF.markers.two,
        map: map
    });
}

function addCampusMarker( lat, lng, name, map ) {
    return new google.maps.Marker({
        position: { lat: parseFloat( lat ), lng: parseFloat( lng ) },
        title: name,
        icon: CF.markers.campus,
        map: map
    });
}

function addCampiMarkers( map ) {
    var markers = [];

    _.each(CF.campi, function(element, index, list) {
        markers.push({
            name: element.name,
            marker: addCampusMarker( element.lat, element.lng, element.name, map )
        });
    });

    return markers;
}

function addStartMarker(lat, lng, map, center, modal) {
    var position = { lat: parseFloat( lat ), lng: parseFloat( lng ) },
        marker = new google.maps.Marker({
            position: position,
            title: 'Partida',
            icon: CF.markers.start,
            map: map,
            draggable: true
        });

    if (typeof center !== 'undefined' && center) {
        map.setCenter(position);
        map.setZoom(centeredZoom);
    }

    marker.addListener('dragend', function(event) {
        startOrReturnMarkerMoved.call(this, 'start', event, modal);
    });

    return marker;
}

function addReturnMarker(lat, lng, map, center, modal) {
    var position = { lat: parseFloat( lat ), lng: parseFloat( lng ) },
        marker = new google.maps.Marker({
            position: position,
            title: 'Retorno',
            icon: CF.markers.return,
            map: map,
            draggable: true
        });

    if (typeof center !== 'undefined' && center) {
        map.setCenter(position);
        map.setZoom(centeredZoom);
    }

    marker.addListener('dragend', function(event) {
        startOrReturnMarkerMoved.call(this, 'return', event, modal);
    });

    return marker;
}

function changeMarkerPosition(marker, lat, lng, map) {
    var position = {lat: lat, lng: lng};

    marker.setPosition(position);

    if (typeof zoom === 'google.maps.Map' ) {
        map.setCenter(position);
        map.setZoom(centeredZoom);
    }
}

function startOrReturnMarkerMoved(type, event, modal) {
    var latLng = event.latLng || false;
    if ( ! latLng ) return;

    modal.find('input[name="' + type + '-lat"]').val( latLng.lat() );
    modal.find('input[name="' + type + '-lng"]').val( latLng.lng() );

    var placeInput = modal.find('input[name="' + type + '-place"]');

    getAddressFromPosition( latLng, function(results) {
        var result = results[0],
            address = results[0].formatted_address || '';

        placeInput.val(address);
    });
}

function createDirection( points, map, callback ) {
    var waypoints = [],
        directionsService = new google.maps.DirectionsService,
        directionsDisplay = new google.maps.DirectionsRenderer( {
            suppressMarkers: true,
            map: map
        } );

    _.each(points, function(element, index, list) {
        waypoints.push( {
            location: element[0] + ',' + element[1],
            stopover: true
        } );
    });

    directionsService.route( {
        origin: waypoints.shift().location,
        destination: waypoints.pop().location,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if ( status === 'OK' ) {
            directionsDisplay.setDirections( response );
        }

        if ( typeof callback !== 'function' ) return;
        callback.call( this, response, status, directionsDisplay );
    } );
}

function getUserLocation(callback, callbackError) {
    if ( ! navigator.geolocation ) {
        console.error('Browser doesn\'t support Geolocation');
        return;
    }

    navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };

        callback.call(this, pos);
    }, function() {
        if (typeof callbackError != 'function') return;
        callbackError.call(this);
    });
}

function getAddressFromPosition(position, callback, callbackError) {
    doAGeocoderSearch( { 'location': position }, callback, callbackError);
}

function getPositionFromAddress(address, callback, callbackError) {
    doAGeocoderSearch( { 'address': address }, callback, callbackError);
}

function doAGeocoderSearch(query, callback, callbackError) {
    var geocoder = new google.maps.Geocoder;
    geocoder.geocode( query, function(results, status) {
        if (status !== 'OK') {
            if (typeof callbackError === 'function') {
                callbackError.call(this, status);
            }

            return;
        }

        callback.call(this, results);
    });
}