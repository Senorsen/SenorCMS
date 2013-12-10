
function c_controller()
{
    this.model = new c_model();
    this.view = new c_view();
};
c_controller.prototype = {
    view: undefined,
    model: undefined
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
/**
 * 交由其他模块调用的回调函数
 * @param  {string} type 回调类型（操作）
 * @param  {array} argv 回调时包括的参数
 * @return {object}      返回对应的对象
 */
c_controller.prototype.callbackd = function(type, argv) {
    
}