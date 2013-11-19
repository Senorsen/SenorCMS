
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
    this.$list_obj.hover(function() {
        
    }, function() {
        
    }).mousewheel(function(event, delta, deltaX, deltaY) {
        $(this).scrollTop($(this).scrollTop() - deltaY * _this.scrollVelocity * 1.5);
    });
};
c_osulist.prototype.btnScroll = function(direction) {
    this.$list_obj.stop(true, false).animate({});
};
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
};
c_osulist.prototype.addList = function(html) {
    var $obj = $(html);
    this.$list_obj.children().append($obj);
    this.refreshView(this.$list_obj);
};
