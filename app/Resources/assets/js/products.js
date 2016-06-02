function loadProducts(url){
    'use strict';

    $.ajax({
        type: 'POST',
        url: url
    })
        .done(function (data){
            loader();

            var productContainer = '';

            if (data.length === 0) {
                productContainer += '<div class="col-md-12 text-center empty-products-container text-muted">' +
                        'Produktų nėra' +
                    '</div>';
            }

            $.each(data, function(key, val){
                productContainer += '<div class="col-md-4 col-sm-6 product-wrap">' +
                    '<div class="product-box">' +
                    '<p class="lead">' +
                    val.brand.title +
                    ' <small class="text-muted">' + val.model.title + '</small>' +
                    '</p>';
                if (val.comment ) {
                    if (val.comment.length > 50) {
                        val.comment = val.comment.substring(0,50) + '...';
                    }

                    productContainer += '<blockquote>' + val.comment + '</blockquote>';

                }
                productContainer += '<p class="features-list">';
                if (val.features.length > 0 ) {
                    productContainer += '<b>Savybės: </b>';
                }
                productContainer += '<small>';

                $.each(val.features, function(key2, val2){
                    productContainer +=  val2.title;
                    if (key2 >= 2) {
                        productContainer += '...';
                        return false;
                    }
                    productContainer += (key2 !== val.features.length - 1) ? ', ' : '.';
                });

                productContainer +=
                    '</small></p>' +
                    '</div>' +
                    '</div>';
            });

            $('.products-holder').html(productContainer);
        });
}