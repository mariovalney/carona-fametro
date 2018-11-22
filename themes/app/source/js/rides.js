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
                goingItems += createRideItem( element, 'return' );
            });

            if ( _.isEmpty( goingItems ) ) {
                goingItems += createEmptyRideItem();
            }

            goingWrapper.removeClass('loading').addClass('done');
            goingWrapper.empty().append(goingItems);

            // Returning
            var returningRides = response.returning || [],
                returningWrapper = $(el).find('.route-returning').find('.available-rides'),
                returningItems = '';

            _.each(returningRides, function(element, index, list) {
                returningItems += createRideItem( element, 'start' );
            });

            if ( _.isEmpty( returningItems ) ) {
                returningItems += createEmptyRideItem();
            }

            returningWrapper.removeClass('loading').addClass('done');
            returningWrapper.empty().append(returningItems);

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
    var name = content.firstName,
        place = ( type == 'return' ) ? content.returnPlace : content.startPlace,
        time = ( type == 'return' ) ? content.returnTime : content.startTime,
        campus = content.campusName,
        message = '';

    if ( type == 'return' ) {
        if ( content.isDriver ) {
            message = 'Pegar [name] no campus [campus] às [time] em direção a [place]';
        } else {
            message = '[name] vai sair do campus [campus] às [time] em direção a [place]';
        }
    } else {
        if ( content.isDriver ) {
            message = 'Pegar [name] em [place] às [time] em direção ao campus [campus]';
        } else {
            message = '[name] vai sair de [place] às [time] em direção ao campus [campus]';
        }
    }

    message = message.replace( '[name]', name ).replace( '[place]', place ).replace( '[time]', time ).replace( '[campus]', campus );
    return '<li class="list-group-item list-group-item-action">' + message + ' <a href="#">Ver no mapa</a></li>';
}

function createEmptyRideItem() {
    return '<li class="list-group-item list-group-item-action">Nenhuma carona encontrada para essa rota</li>';
}