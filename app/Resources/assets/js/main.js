function loadProducts(url){
    'use strict';

    $.ajax({
        type: 'POST',
        url: url
    })
        .done(function (data) {

            var productContainer = '';

            $.each(data, function(key, val){
                productContainer += '<div class="col-md-3 product-wrap">' +
                    '<div class="product-box">' +
                    '<div>' + val.brand.title + '</div>' +
                    '<div>' + val.model.title + '</div>' +
                    '<div>' + val.comment + '</div>' +
                    '</div>' +
                    '</div>';
            });

            $('.products-holder').html(productContainer);
        });
}

function loader(){
    'use strict';

    $('.products-holder').html('<div class="loader"></div>');
}

(function($){
    'use strict';

    loader();

    var $brand = $('#product_brand');
    $brand.change(function() {
        var $form = $(this).closest('form');
        var data = {};
        data[$brand.attr('name')] = $brand.val();
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                $('#product_model').parent().html(
                    $(html).find('#product_model')
                );
                $('.selectpicker').selectpicker('refresh');
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
            loadProducts(getProductsUrl);
            var parsedData = $.parseJSON(data);
            if(parsedData.status === 'success'){
                alert('!!');
            }
        });
    });



})(jQuery);