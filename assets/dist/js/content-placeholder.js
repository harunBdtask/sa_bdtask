// preload get data
function preloader_ajax(){
    $('.page-loader-wrapper').show();
}

$(document).ajaxStop(function(e) {
    $('.page-loader-wrapper').hide();
});
