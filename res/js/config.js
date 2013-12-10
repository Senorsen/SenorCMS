
// Static Configuration

var config = {
    debug: 1,
    ajax_no_cache: 1
};

(function($) {
    $.ajaxSetup({
        async: true,
        cache: false,    //后期在代码中手动缓存，以避免浪费
        timeout: 10000
    });

})(jQuery);