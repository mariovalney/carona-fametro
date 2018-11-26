function inviteEvents() {
    $('.show-invite-route-panel a').on('click', function(event) {
        event.preventDefault();

        $('#invite-route-panel').slideToggle();
    });

    $(document).on('ajax_form_aceitar-convite_done', function(event, data) {
        if ( data.status !== 'success' ) return;

        setTimeout(function() {
            window.location.reload()
        }, 2000);
    });
}

function mapsInvite() {
    var element = $('#map-invite');
    if ( ! element.length ) return;

    var map = new google.maps.Map(element.get(0), {
            center: default_pos,
            placeInput: false,
            zoom: 13
        }),
    route_lat = element.data('route-lat'),
    route_lng = element.data('route-lng'),
    route_campus = element.data('route-campus'),
    ride_lat = element.data('ride-lat'),
    ride_lng = element.data('ride-lng'),
    ride_campus = element.data('ride-campus');

    if ( ! route_lat || ! route_lng || ! route_campus || ! ride_lat || ! ride_lng || ! ride_campus ) {
        swal( 'Ops...', 'Não conseguimos carregar a rota. Tente novamente', 'error' );
        return;
    }

    // Route
    var direction = [];

    // Campus Data
    _.each(CF.campi, function(element, index, list) {
        if ( route_campus === element.name ) {
            route_campus = {
                name: element.name,
                lat: element.lat,
                lng: element.lng,
            };
        }

        if ( ride_campus === element.name ) {
            ride_campus = {
                name: element.name,
                lat: element.lat,
                lng: element.lng,
            };
        }
    });

    if ( ! ( route_campus.name || false ) || ! ( ride_campus.name || false ) ) {
        swal( 'Ops...', 'Não conseguimos carregar a rota. Tente novamente', 'error' );
        return;
    }

    // Add Route Campus
    direction.push( [ route_campus.lat, route_campus.lng ] );
    addCampusMarker( route_campus.lat, route_campus.lng, route_campus.name, map );

    // Add Ride Campus, if another
    if ( route_campus.name !== ride_campus.name ) {
        direction.push( [ ride_campus.lat, ride_campus.lng ] );
        addCampusMarker( ride_campus.lat, ride_campus.lng, ride_campus.name, map );
    }

    // Add Ride Point
    direction.push( [ ride_lat, ride_lng ] );
    addNumeredMarker( 2, ride_lat, ride_lng, map );

    // Add Route Point
    direction.push( [ route_lat, route_lng ] );
    addNumeredMarker( 1, route_lat, route_lng, map );

    // Create Direction
    direction.reverse();

    createDirection( direction, map, function( response, status, renderer ) {
        renderer.setPanel( document.getElementById( 'invite-route-panel' ) );
    } );
}