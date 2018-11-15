function modalEvents() {
    $(document).on('shown.bs.modal', '.modal-routes', function () {
        $('html').addClass('modal-routes-open');
    });

    $(document).on('hidden.bs.modal', '.modal-routes', function () {
        $('html').removeClass('modal-routes-open');
    });
}