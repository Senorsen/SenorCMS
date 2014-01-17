/**
 * @author Senorsen zhs490770@foxmail.com
 * 
 */


function c_model()
{
    
};
c_model.prototype = {
    url: {
        "getArticle": ["article/disp", 1],
        "getList": ["article/category", 1]
    },
    data_handler: {
        "getArticle": [["id"], []],
        "getList": [["category", "page"], []]
    }
};

c_model.prototype.data_handle = function(type, args) {
    var is_ajax = 1;
    var arg_get = [], arg_post = [];
    // handle GET data
    for (var i in this.data_handler[0]) {
        //假定参数一定存在，必须存在，有存在的义务
        var this_key = this.data_handler[0][i];
        arg_get.push(args[this_key]);
    }
    // handle POST data
    for (var i in this.data_handler[1]) {
        var this_key = this.data_handler[1][i];
        arg_post.push(args[this_key]);
    }
    var get_url = arg_get.join('/');
    return {get: get_url, post: arg_post, fr: this.url[type][1]};
}
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
    var url = url_prefix+this.url[interface][0]+'/'+getarg.join('/');
    var method = (typeof postdata == 'undefined' || postdata == []) ? 'GET' : 'POST';
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
    
};
