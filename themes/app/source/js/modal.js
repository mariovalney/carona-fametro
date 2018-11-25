function modalEvents() {
    $(document).on('shown.bs.modal', '.modal-routes', function () {
        $('html').addClass('modal-routes-open');
    });

    $(document).on('hidden.bs.modal', '.modal-routes', function () {
        $('html').removeClass('modal-routes-open');
    });
}

var rideModal = {
    map: false,
    renderer: false,
    routeCampusMarker: false,
    routeMarker: false,
    rideCampusMarker: false,
    rideMarker: false,
    route: false
};

// TODO: Refactory to accept a object with data
function openRideModal( route_id, route_description, route_point, route_campus, route_is_driver, ride_user, ride_point, ride_campus ) {
    var modal = $('#modal-rides'),
        direction = [];

    if ( ! modal.length || ! route_id || _.isEmpty( ride_user ) ) {
        swal( 'Ops...', 'Não foi possível exibir a rota.', 'error' );
        return;
    }

    // Create Map
    if ( ! rideModal.map ) {
        rideModal.map = createRideMap();
    }

    rideModal.map.setZoom( 13 );

    // Erase All Data
    if ( rideModal.routeCampusMarker ) rideModal.routeCampusMarker.setMap( null );
    if ( rideModal.rideCampusMarker ) rideModal.rideCampusMarker.setMap( null );
    if ( rideModal.routeMarker ) rideModal.routeMarker.setMap( null );
    if ( rideModal.rideMarker ) rideModal.rideMarker.setMap( null );
    if ( rideModal.renderer ) rideModal.renderer.setMap( null );

    $('#modal-rides-panel').empty();

    // Texts and Data
    modal.find('input[name="route-id"]').val( route_id );
    modal.find('input[name="invited-user-id"]').val( ride_user.ID );
    modal.find('.invited-user-name').html( ride_user.name );
    modal.find('.route-description').html( route_description );
    modal.find('.btn-submit-invite').text( ( route_is_driver ) ? 'Enviar convite' : 'Solicitar carona' );

    modal.find('.avatar').empty();
    if ( ! _.isEmpty( ride_user.avatar ) ) {
        modal.find('.avatar').append( '<img src="' + ride_user.avatar + '">' );
    }

    // Open Modal
    modal.modal('show');

    modal.one('shown.bs.modal', function(event) {
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
            swal( 'Ops...', 'Não foi possível exibir a rota.', 'error' ).then(function() {
                modal.modal('hide');
            });

            return;
        }

        // Add Route Campus
        direction.push( [ route_campus.lat, route_campus.lng ] );
        rideModal.routeCampusMarker = addCampusMarker( route_campus.lat, route_campus.lng, route_campus.name, rideModal.map );

        // Add Ride Campus, if another
        if ( route_campus.name !== ride_campus.name ) {
            direction.push( [ ride_campus.lat, ride_campus.lng ] );
            rideModal.rideCampusMarker = addCampusMarker( ride_campus.lat, ride_campus.lng, ride_campus.name, rideModal.map );
        }

        // Add Ride Point
        ride_point = ride_point.split(',');
        ride_point = { lat: ride_point[0], lng: ride_point[1] };

        direction.push( [ ride_point.lat, ride_point.lng ] );
        rideModal.rideMarker = addNumeredMarker( 2, ride_point.lat, ride_point.lng, rideModal.map );

        // Add Route Point
        route_point = route_point.split(',');
        route_point = { lat: route_point[0], lng: route_point[1] };

        direction.push( [ route_point.lat, route_point.lng ] );
        rideModal.routeMarker = addNumeredMarker( 1, route_point.lat, route_point.lng, rideModal.map );

        // Create Direction
        direction.reverse();

        rideModal.route = createDirection( direction, rideModal.map, function( response, status, renderer ) {
            rideModal.renderer = renderer;
            renderer.setPanel( document.getElementById( 'modal-rides-panel' ) );
        } );
    });
}