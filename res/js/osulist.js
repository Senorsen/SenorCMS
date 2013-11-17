
function c_osulist($list_obj)
{
    this.scrollVelocity = 20;
    this.scrHeight = $(window).height();
    this.$list_obj = $list_obj;
    this.initListLayer();
    this.refreshView(this.$list_obj);
};
c_osulist.prototype = {
    is_on_scroll: 0,
    refresh_view_intvid: -1,
    sel_callback: function() {},
    on_important_scroll: false,
    on_sel: false
};
c_osulist.prototype.initListLayer = function() {
    var _this = this;
    this.$list_obj.on('mousemove', function(e) {
        var rel_top = e.pageY-$('body').scrollTop()
        if (rel_top <= $(window).height() * 0.15) _this.scrollUp(this);
        else if (rel_top >=  $(window).height() * 0.85) _this.scrollDown(this);
        else _this.scrollStop(this);
    }).mouseleave(function() {
        _this.scrollStop(this);
    }).mousewheel(function(event, delta, deltaX, deltaY) {
        $(this).scrollTop($(this).scrollTop() - deltaY * _this.scrollVelocity * 2);
    });
}
c_osulist.prototype.scrollStop = function(o) {
    console.log('scrollStop');
    this.is_on_scroll = 0;
    //$(o).stop(true, false);
}
c_osulist.prototype.scrollUp = function(o, _innercall) {
    var _this = this;
    if (this.is_on_scroll && !_innercall) return;
    this.is_on_scroll = -1;
    $(o).animate({scrollTop: '-='+this.scrollVelocity}, 80, "linear", function() {
        if ($(this).scrollTop() <= 0)
        {
            _this.scrollStop(this);
            return;
        }
        _this.scrollUp(this, 1);
    });
}
c_osulist.prototype.scrollDown = function(o, _innercall) {
    var _this = this;
    if (this.is_on_scroll && !_innercall) return;
    this.is_on_scroll = 1;
    $(o).animate({scrollTop: '+='+this.scrollVelocity}, 80, "linear", function() {
        if ($(this).scrollTop() >= $(this).height()-$(window).height())
        {
            _this.scrollStop(this);
            return;
        }
        _this.scrollDown(this, 1);
    });
}
c_osulist.prototype.refreshView = function($o) {
    var _this = this;
    var $ab_o = $o.find('.article-button');
    $ab_o.hover(function() {
        if ($(this).hasClass('onsel')) return;
        if (!_this.on_sel)
        {
            $(this).removeClass('hov').stop(true, false).animate({right: -50}, 200, "swing");
        }
        else
        {
            $(this).removeClass('hov').stop(true, false).animate({right: -100}, 200, "swing");
        }
    }, function() {
        if ($(this).hasClass('onsel')) return;
        if (!_this.on_sel)
        {
            $(this).removeClass('hov').stop(true, false).animate({right: -100}, 200, "swing");
        }
        else
        {
            $(this).removeClass('hov').stop(true, false).animate({right: -160}, 200, "swing");
        }
    }).click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        _this.on_sel = true;
        var this_button_id = this.id;
        $ab_o.each(function() {
            if (this.id != this_button_id)
            {
                $(this).removeClass('onsel').animate({right: -160});
            }
        });
        console.log($o.scrollTop()+' '+$(this).offset().top);
        $(this).addClass('onsel').animate({right: -40});
        $o.animate({scrollTop: $(this).offset().top},400);
        _this.sel_callback(JSON.parse(decodeURIComponent($(this).attr('x-dataarea-json'))));
    }).animate({right: -100}, 60);
};
c_osulist.prototype.setCallback = function(callback) {
    this.sel_callback = callback;
}
c_osulist.prototype.addList = function(obj) {
    
}
