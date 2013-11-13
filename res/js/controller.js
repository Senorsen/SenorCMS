
function c_controller()
{
    this.model = new c_model();
    this.view = new c_view();
};
c_controller.prototype = {
    view: undefined,
    model: undefined
};
