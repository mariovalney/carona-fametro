jQuery(document).ready(function($) {
    ajaxForm();

    createMasks();
    validateForms();

    modalEvents();
    routesEvents();

    mapsRoutes();

    // SWAL hardcoded
    for (var i = 0; i < CF.swal.length; i++) {
        swal( ( CF.swal[i][0] || '' ), ( CF.swal[i][1] || '' ), ( CF.swal[i][2] || 'info' ) );
    }
});