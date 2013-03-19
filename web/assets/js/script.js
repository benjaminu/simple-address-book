jQuery(document).ready(function($){
    window.set_flash_message = function(flashes, type, message) {
        flashes.find('.alert').remove();

        flashes.append(
            '<div class="alert alert-' + type + '">' +
            '<span><a class="close" data-dismiss="alert" href="#">&times;</a></span>' +
            message + '</div>'
        );

        flashes.show().find('.alert').delay(10000).fadeOut(1000);
    };
});