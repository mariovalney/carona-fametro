function ajaxForm() {
    $('form.ajax-form').each(function(index, el) {
        var form = $(el);

        form.on('submit', function(event) {
            event.preventDefault();

            if (form.hasClass('submiting')) return false;
            form.addClass('submiting');
            $('body').addClass('loading');

            var action = form.attr('action'),
                method = form.attr('method');

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

                swal ( title,  message ,  status );
            })
            .fail(function() {
                swal ( 'Oops',  'Houve um error ao enviar o formulário.' ,  'error' );
            })
            .always(function() {
                form.removeClass('submiting');
                $('body').removeClass('loading');
            });

            return false;
        });

    });
}