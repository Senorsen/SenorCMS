/**
 * @author Senorsen zhs490770@foxmail.com
 * 
 */


function c_model()
{
    
};
c_model.prototype = {
    url: {
        "getArticle": "article/disp",
        "getList": "article/category"
    },
};

/**
 * 通过相应接口获得数据
 * @param  {string} interface 
 * @param  {array} getarg    [description]
 * @param  {object} postdata  [description]
 * @param {[type]} [varname] [description]
 * @return {[type]}           [description]
 */
c_model.prototype.fetch = function(interface, getarg, postdata, force_refresh, callback) {
    if (typeof getarg == 'object' && !$.isArray(getarg)) {
        force_refresh = postdata;
        postdata = getarg;
        getarg = [];
    }
    if ($.isArray(getarg) && typeof postdata != 'object') {
        force_refresh = postdata;
        postdata = undefined;
    }
    if (typeof force_refresh == 'undefined') {
        //默认应进行缓存，除非是指定不需要缓存的情况
        force_refresh = 0;
    }
    if (typeof getarg == 'undefined') {
        getarg = [];
    }
    callback = (typeof callback == 'function')?callback:(typeof force_refresh == 'function'?force_refresh:(typeof postdata == 'function'?postdata:(typeof getarg == 'function'?getarg:function() {})));
    var url = url_prefix+this.url[interface]+'/'+getarg.join('/');
    var method = (typeof postdata == 'undefined') ? 'GET' : 'POST';
    $.ajax({
        url: url,
        type: method,
        dataType: 'json',
        data: postdata,
    })
    .done(function(data) {
        console.log("success & callback...");
        callback(data);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
