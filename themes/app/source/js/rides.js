function rideOnMap( parentSelector ) {
    var route_id = $(this).parents( parentSelector ).data('route-id'),
        route_description = $(this).parents( parentSelector ).find('.route-description').html(),
        route_point = $(this).parents( parentSelector ).data('route-point'),
        route_campus = $(this).parents( parentSelector ).data('route-campus'),
        route_is_driver = $(this).parents( parentSelector ).data('route-is-driver'),
        type = $(this).data('type'),
        ride_id = $(this).data('ride-id'),
        ride_user = {
            name: $(this).data('ride-user-name'),
            avatar: $(this).data('ride-user-avatar'),
        },
        ride_point = $(this).data('ride-point'),
        ride_campus = $(this).data('ride-campus');

    if ( ! route_id, ! route_description || ! route_point || ! route_campus || ! ride_user || ! ride_point || ! ride_campus ) {
        swal( 'Ops...', 'Não foi possível exibir a rota.', 'error' );
        return;
    }

    openRideModal( type, route_id, route_description, route_point, route_campus, route_is_driver, ride_id, ride_user, ride_point, ride_campus );
}

function ridesEvents() {
    $('body').on('click', '.route-going li', function( event ) {
        event.preventDefault();
        rideOnMap.call(this, '.route-going');
    });

    $('body').on('click', '.route-returning li', function( event ) {
        event.preventDefault();
        rideOnMap.call(this, '.route-returning');
    });
}

function searchRides() {
    var ridesList = $('#rides-list');
    if ( ! ridesList.length ) return;

    ridesList.find('.item[data-route-id]').each(function(index, el) {
        var routeId = $(el).data('route-id');
        if ( ! routeId ) return;

        // Loading
        var loadingTime = 0,
            loadingText = 'Procurando rotas';

        $(el).find('.available-rides').addClass('loading').append( '<p class="loading-text">' + loadingText + '</p>' );

        var loadingIntervarl = setInterval(function() {
            loadingTime = ( loadingTime > 2 ) ? 0 : ( loadingTime + 1 );

            $(el).find('.loading-text').text( loadingText + ' .'.repeat( loadingTime ) );
        }, 500);

        // Search for rides
        $.ajax({
            url: '/ajax/caronas',
            type: 'POST',
            data: {
                route: routeId
            },
        })
        .done(function(response) {
            response = JSON.parse(response);

            // Going
            var goingRides = response.going || [],
                goingWrapper = $(el).find('.route-going').find('.available-rides'),
                goingItems = '';

            _.each(goingRides, function(element, index, list) {
                goingItems += createRideItem( element, 'start' );
            });

            if ( _.isEmpty( goingItems ) ) {
                goingItems += createEmptyRideItem();
            }

            goingWrapper.removeClass('loading').addClass('done');
            goingWrapper.find('ul').empty().append(goingItems);

            // Returning
            var returningRides = response.returning || [],
                returningWrapper = $(el).find('.route-returning').find('.available-rides'),
                returningItems = '';

            _.each(returningRides, function(element, index, list) {
                returningItems += createRideItem( element, 'return' );
            });

            if ( _.isEmpty( returningItems ) ) {
                returningItems += createEmptyRideItem();
            }

            returningWrapper.removeClass('loading').addClass('done');
            returningWrapper.find('ul').empty().append(returningItems);

            // Remove Loading
            clearInterval( loadingIntervarl );
            $(el).find('.available-rides .loading-text').remove();
        })
        .fail(function() {
            clearInterval( loadingIntervarl );
            $(el).find('.available-rides').removeClass('loading').find('.loading-text').remove();
            $(el).find('.available-rides').addClass('done').find('> p').addClass('error').text('Ops... Houve um error ao buscar caronas para essa rota.');
        });
    });
}

function createRideItem( content, type ) {
    var name = content.displayName,
        place = ( type == 'return' ) ? content.returnPlace : content.startPlace,
        time = ( type == 'return' ) ? content.returnTime : content.startTime,
        campus = content.campusName,
        ride_id = content.ID,
        ride_user_avatar = content.avatar,
        message = '',
        ride_point = '',
        attrs = 'data-type="[type]" data-ride-id="[ride_id]" data-ride-user-name="[ride_user_name]" data-ride-user-avatar="[ride_user_avatar]" data-ride-point="[ride_point]" data-ride-campus="[campus]"';

    if ( type == 'return' ) {
        ride_point = content.returnLat + ',' + content.returnLng;

        if ( content.isDriver ) {
            message = 'Pegar [name] no campus [campus] às [time] em direção a [place]';
        } else {
            message = '[name] vai sair do campus [campus] às [time] em direção a [place]';
        }
    } else {
        ride_point = content.startLat + ',' + content.startLng;

        if ( content.isDriver ) {
            message = 'Pegar [name] em [place] às [time] em direção ao campus [campus]';
        } else {
            message = '[name] vai sair de [place] às [time] em direção ao campus [campus]';
        }
    }

    message = message.replace( '[name]', name ).replace( '[place]', place ).replace( '[time]', time ).replace( '[campus]', campus );
    attrs = attrs.replace( '[type]', type ).replace( '[ride_id]', ride_id ).replace( '[ride_user_name]', name ).replace( '[ride_user_avatar]', ride_user_avatar ).replace( '[ride_point]', ride_point ).replace( '[campus]', campus );

    return '<li class="list-group-item list-group-item-action" ' + attrs + ' title="Ver no mapa">' + message + '</li>';
}

function createEmptyRideItem() {
    return '<li class="list-group-item list-group-item-action">Nenhuma carona encontrada para essa rota</li>';
}