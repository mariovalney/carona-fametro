'use strict';

(function(jQuery) {
    function validateCnpj(cnpj) {
        cnpj = cnpj.toString().replace(/[^0-9]/g, '');

        var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
        digitos_iguais = 1;

        if (cnpj.length < 14 && cnpj.length < 15) {
            return false;
        }

        for (i = 0; i < cnpj.length - 1; i++) {
            if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
                digitos_iguais = 0;
                break;
            }
        }

        if (digitos_iguais) {
            return false;
        }

        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0, tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }

        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

        if (resultado != digitos.charAt(0)) {
            return false;
        }

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }

        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;

        if (resultado != digitos.charAt(1)) {
            return false;
        }

        return true;
    }

    function validateCpf(value) {
        value = value.toString().replace(/[^0-9]/g, '');

        var invalidos = [
            '111.111.111-11',
            '222.222.222-22',
            '333.333.333-33',
            '444.444.444-44',
            '555.555.555-55',
            '666.666.666-66',
            '777.777.777-77',
            '888.888.888-88',
            '999.999.999-99',
            '000.000.000-00'
        ];

        for (i = 0; i < invalidos.length; i++) {
            if (invalidos[i] == value) {
                return false;
            }
        }

        value = value.replace("-", "");
        value = value.replace(/\./g, "");

        // Validating First Digit
        add = 0;

        for (i = 0; i < 9; i++) {
            add += parseInt(value.charAt(i), 10) * (10 - i);
        }

        rev = 11 - (add % 11);

        if (rev == 10 || rev == 11) {
            rev = 0;
        }

        if (rev != parseInt(value.charAt(9), 10)) {
            return false;
        }

        // Validating Second Digit
        add = 0;

        for (i = 0; i < 10; i++) {
            add += parseInt(value.charAt(i), 10) * (11 - i);
        }

        rev = 11 - (add % 11);

        if (rev == 10 || rev == 11) {
            rev = 0;
        }

        if (rev != parseInt(value.charAt(10), 10)) {
            return false;
        }

        return true;
    }

    jQuery.validator.addMethod('cnpj', function(value, element) {
        return this.optional(element) || validateCnpj(value);
    }, "Informe um CNPJ válido.");

    jQuery.validator.addMethod('cpf', function(value, element) {
        return this.optional(element) || validateCpf(value);
    }, "Informe um CPF válido.");

    jQuery.validator.addMethod('document', function(value, element) {
        value = value.toString().replace(/[^0-9]/g, '');
        if ( value.length > 11 ) {
            return this.optional(element) || validateCnpj(value);
        }

        return this.optional(element) || validateCpf(value);
    }, "Informe um CNPJ ou um CPF válido.");
})(jQuery);

jQuery.validator.addMethod('telefone', function(value, element, param) {
    var validDDD = [11, 12, 13, 14, 15, 16, 17, 18, 19, 21, 22, 24, 27, 28, 31, 32, 33, 34, 35, 37, 38, 41, 42, 43, 44, 45, 46, 47, 48, 49, 51, 53, 54, 55, 61, 62, 63, 64, 65, 66, 67, 68, 69, 71, 73, 74, 75, 77, 79, 81, 82, 83, 84, 85, 86, 87, 88, 89, 91, 92, 93, 94, 95, 96, 97, 98, 99];

    value = value.toString().replace(/[\D]/g, '');

    if (value.length < 10) return false;

    var ddd = value.slice(0, 2),
        number = value.slice(2);

    if (! jQuery.inArray(ddd, validDDD)) return false;

    if (number.length == 9 && number.charAt(0) != '9') return false;

    return true;
}, 'Informe um telefone válido.');

jQuery.validator.addMethod('required-checkbox-group', function(value, element, params) {
    var group = [],
        name = jQuery(element).attr('name');

    if (!name) return true;

    group = jQuery('input[name="' + name + '"]');

    for (var i = 0; i < group.length; i++) {
        if (jQuery(group[i]).prop('checked')) return true;
    }

    return false;
}, '');

jQuery.validator.addMethod('moneyMin', function(value, element, param) {
    value = value.replace('.', '').replace(',', '.');
    value = value.replace(/[^0-9\.]/g, '');
    value = parseFloat( value );

    return value >= param;
}, function(value) {
    value = value.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    value = 'R$ ' + value.toString().replace(',', 'A').replace('.', ',').replace('A', '.');
    return jQuery.validator.format( 'O valor mínimo é {0}.', value );
} );

jQuery.validator.addMethod('moneyMax', function(value, element, param) {
    value = value.replace('.', '').replace(',', '.');
    value = value.replace(/[^0-9\.]/g, '');
    value = parseFloat( value );

    return value <= param;
}, function(value) {
    value = value.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    value = 'R$ ' + value.toString().replace(',', 'A').replace('.', ',').replace('A', '.');
    return jQuery.validator.format( 'O valor máximo é {0}.', value );
} );

function createMasks() {
    // Nineth Digit behavior
    var ninethDigitMask = function( val ) {
        return ( val.replace( /\D/g, '' ).length === 11 ) ? '(00) 00000-0000' : '(00) 0000-00009';
    }

    // Multiple Documents Behaviour
    var multipleDocumentsMask = function( val, e, field, options ) {
        return ( val.replace( /\D/g, '' ).length > 11 ) ? '00.000.000/0000-00' : '000.000.000-00999';
    }

    // Masks
    $('.mask-date').mask( '00/00/0000' );
    $('.mask-time').mask( '00:00:00' );
    $('.mask-date_time').mask( '00/00/0000 00:00:00' );
    $('.mask-mixed').mask( 'AAA 000-S0S' );
    $('.mask-cep').mask( '00000-000' );
    $('.mask-cpf').mask( '000.000.000-00', { reverse: true } );
    $('.mask-cnpj').mask( '00.000.000/0000-00', { reverse: true } );
    $('.mask-money').mask( '000.000.000.000.000,00', { reverse: true } );
    $('.mask-money2').mask( 'R$ #.##0,00', { reverse: true } );

    $('.mask-document').mask( multipleDocumentsMask, { onKeyPress: function(v, e, f, o) { f.mask( multipleDocumentsMask.apply( {}, arguments ), 0 ); } } );
    $('.mask-phone').mask( ninethDigitMask, { onKeyPress: function(v, e, f, o) { f.mask( ninethDigitMask.apply( {}, arguments ), 0 ); } } );

    $('.number-only').keyup(function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });
}

function validateForms() {
    $('.validate-form').each(function(index, el) {
        var form = $(el);

        form.validate();

        form.find('input.mask-phone').rules('add', { telefone: true });

        form.find('input.mask-cnpj').rules('add', {
            cnpj: true,
            messages: {
                cnpj: 'Preencha com um CNPJ válido.'
            }
        });

        form.find('input.mask-cpf').rules('add', {
            cpf: true,
            messages: {
                cpf: 'Preencha com um CPF válido.'
            }
        });

        form.find('input.mask-document').rules('add', {
            document: true,
            messages: {
                document: 'Preencha com um CNPJ ou um CPF válido.'
            }
        });

        form.find('input.number-only').rules('add', {
            digits: true,
            min: 0
        });

        // If the form has simulation fields
        form.find('[data-validate-simulation-min]').each(function(index, input) {
            $(input).rules('add', {
                moneyMin: parseFloat( $(input).data('validate-simulation-min') ),
            });
        });

        form.find('[data-validate-simulation-max]').each(function(index, input) {
            $(input).rules('add', {
                moneyMax: parseFloat( $(input).data('validate-simulation-max') ),
            });
        });
    });
}