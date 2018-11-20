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

            goingWrapper.removeClass('loading').addClass('done');
            goingWrapper.empty().append(goingItems);

            _.each(goingRides, function(element, index, list) {
                goingItems += createRideItem( element );
            });

            // Returning
            var returningRides = response.returning || [],
                returningWrapper = $(el).find('.route-returning').find('.available-rides'),
                returningItems = '';

            _.each(returningRides, function(element, index, list) {
                returningItems += createRideItem( element );
            });

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

function createRideItem( content ) {
    return '<li class="list-group-item list-group-item-action">' + content + '</li>';
}