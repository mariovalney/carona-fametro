function routesEvents() {
    for (var i = 0; i < 7; i++) {
        $('#modal-route-' + i).on('submit', 'form', function(event) {
            if ( $(this).find('input[name="start-place"]').is(':focus') || $(this).find('input[name="return-place"]').is(':focus') ) {
                return false;
            }
        });
    }

    $(document).on('ajax_form_rotas_done', function(event, data) {
        var route = data.route || {};

        $('.modal-routes').modal('hide');

        if ( typeof route.ID === 'undefined' ) return;
    });
}