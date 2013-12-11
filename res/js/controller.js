
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
        type: 'getArticle',
        callback: this.callbackd
    };
}