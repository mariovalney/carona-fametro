function ajaxForm() {
    $('body').on('submit', 'form.ajax-form', function(event) {
        event.preventDefault();

        var form = $(this);

        if (form.hasClass('submiting')) return false;
        form.addClass('submiting');
        $('body').addClass('loading');

        var action = form.attr('action'),
            method = form.attr('method'),
            ajax_form_submitted = action.split( '/ajax/' );

        ajax_form_submitted = ajax_form_submitted[ ajax_form_submitted.length - 1 ];
        ajax_form_submitted = ajax_form_submitted.replace( '\/', '_' ).replace( /[^a-zA-Z-_]/ , '' );
        ajax_form_submitted = 'ajax_form_' + ajax_form_submitted;

        action = ( action ) ? action : '/';
        method = ( method ) ? method : 'POST';

        $.ajax({
            url: action,
            type: method,
            data: form.serializeArray(),
        })
        .done(function(response) {
            response = JSON.parse(response);

            var title = response.title || '',
                message = response.message || 'Tudo certo!',
                status = response.status || 'success';

            swal( title,  message ,  status );

            $(document).trigger( ajax_form_submitted + '_done', response);
        })
        .fail(function() {
            swal ( 'Oops',  'Houve um error ao enviar o formulário.' ,  'error' );

            $(document).trigger( ajax_form_submitted + '_fail');
        })
        .always(function() {
            form.removeClass('submiting');
            $('body').removeClass('loading');
        });

        return false;
    });
}