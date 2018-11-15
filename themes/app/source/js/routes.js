function routesEvents() {
    $(document).on('ajax_form_rotas_done', function(event, data) {
        var route = data.route || {};

        $('.modal-routes').modal('hide');

        if ( typeof route.ID === 'undefined' ) return;

        // $('#modal-routes-' + route.dow)

        // $('#modal-routes-' + route.dow).find('id').text('some text');
        // $('#modal-routes-' + route.dow).find('user-id').text('some text');
        // $('#modal-routes-' + route.dow).find('start-lat').text('some text');
        // $('#modal-routes-' + route.dow).find('start-lng').text('some text');
        // $('#modal-routes-' + route.dow).find('end-lat').text('some text');
        // $('#modal-routes-' + route.dow).find('end-lng').text('some text');
        // $('#modal-routes-' + route.dow).find('start-time').text('some text');
        // $('#modal-routes-' + route.dow).find('return-time').text('some text');
        // $('#modal-routes-' + route.dow).find('campus-name').text('some text');
        // $('#modal-routes-' + route.dow).find('is-driver').text('some text');
        // $('#modal-routes-' + route.dow).find('dow').text('some text');
    });
}