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

        if ( route == 'all' ) {
            setTimeout(function() {
                window.location.reload()
            }, 2000);

            return;
        }

        if ( typeof route.ID === 'undefined' ) return;

        // TODO: Update data without refresh
        setTimeout(function() {
            window.location.reload()
        }, 2000);
    });

    $('input[name="all-routes"]').on('change', function(event) {
        var input = $(this);

        if (!input.is(':checked')) return;

        swal({
            title: 'Você tem certeza?',
            text: 'Ao salvar essa rota, todas as outras serão alteradas. Isso não pode ser desfeito.',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((overwrite) => {
            if (!overwrite) {
                input.prop('checked', false);
            }
        });
    });
}