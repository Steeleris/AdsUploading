function removeErrorBorders(){
    'use strict';

    $('.selectpicker').each(function(){
        $(this).on('change.bs.select', function (){
            $(this).closest('.select-wrapper').removeClass('error-input');
        });
    });

    $('#upload-form').find('textarea').bind('input propertychange', function() {
        $(this).removeClass('error-input');
    });
}

function writeLogs(content, type){
    'use strict';

    return $('.logs-container').html('<div class="logs-box '+ type +'-box">' +
        '<ul>' +
        content +
        '</ul>' +
        '</div>').children();
}