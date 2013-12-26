
function c_controller()
{
    this.model = new c_model();
    this.view = new c_view();
};
c_controller.prototype = {
    view: undefined,
    model: undefined,
    
};
/**
 * 向各个模块注册函数
 * @return {undefined} 无返回值
 */
c_controller.prototype.setupCallback = function() {
    this.view.article.controller_callback = {
        type: ['model', 'getArticle'],
        callback: this.callbackd
    };
};

c_controller.prototype.callbackd = function(type, args) {
    if (type[0] == 'model') {
        var model_args = this.model.data_handle.apply(this.model, [args]);
        this.model.fetch.apply(this.model, [type[1], model_args.get, model_args.post, model_args.fr]);
    }
}