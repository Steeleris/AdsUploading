(function($){
    'use strict';

    loader();

    var $brand = $('#product_brand');
    $brand.change(function(){
        var $form = $(this).closest('form');
        var data = {};
        data[$brand.attr('name')] = $brand.val();
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(data){
                $('#product_model').closest('.select-wrapper').html(
                    $(data.html).find('#product_model')
                );
                $('.selectpicker').selectpicker('refresh');
                removeErrorBorders();
            }
        });
    });

    var getProductsUrl = Routing.generate('get_products');

    loadProducts(getProductsUrl);

    $('#upload-form').submit(function(e){

        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
        .done(function (data) {
            if (data.status === 'success') {
                $('#upload-form')[0].reset();
                $('.selectpicker').selectpicker('refresh');

                var successString = '<li><b>Skelbimas sėkmingai įkeltas</b></li>';
                writeLogs(successString, 'success').delay(2000).fadeOut(300, function(){ $(this).remove(); });

                loadProducts(getProductsUrl);
            } else if(data.status === 'fail') {
                var errorsString = '';

                $.each(data.errors, function(key){
                    $.each(data.errors[key], function(key2, val2){
                        errorsString += '<li><b>' + val2 + '</b></li>';
                    });

                    var select = $('.error-' + key + '-select');
                    var input = $('.error-' + key + '-input');

                    if (select.length) {
                        select.addClass('error-input');
                    } else if (input.length) {
                        input.addClass('error-input');
                    }
                });

                writeLogs(errorsString, 'errors');
            }
        });
    });

    removeErrorBorders();
})(jQuery);