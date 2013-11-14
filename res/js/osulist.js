
function c_osulist($list_obj)
{
    this.calcPosxArray($(window).height());
    this.$list_obj = $list_obj;
    this.initListLayer();
    this.refreshView($list_obj);
};
c_osulist.prototype = {
    is_on_scroll: 0,
    clicked_id: -1
};
c_osulist.prototype.initListLayer = function() {
    var _this = this;
    this.$list_obj.on('mousemove', function(e) {
        var rel_top = e.pageY-$('body').scrollTop()
        console.log(e.pageX+' '+rel_top);
        if (rel_top <= $(window).height() * 0.2) _this.scrollUp(this);
        else if (rel_top >=  $(window).height() * 0.8) _this.scrollDown(this);
        else _this.scrollStop(this);
    });
    this.$list_obj.mouseleave(function() {
        _this.scrollStop(this);
    });
    this.$list_obj.find('.article-button').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        
    });
}
c_osulist.prototype.scrollStop = function(o) {
    console.log('scrollStop');
    this.is_on_scroll = 0;
    $(o).stop(true, false);
}
c_osulist.prototype.scrollUp = function(o, _innercall) {
    var _this = this;
    if (this.is_on_scroll && !_innercall) return;
    console.log('scrollUp');
    this.is_on_scroll = -1;
    $(o).animate({scrollTop: '-=100px'}, 800, "linear", function() {
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
    console.log('scrollDown');
    this.is_on_scroll = 1;
    $(o).animate({scrollTop: '+=100px'}, 800, "linear", function() {
        if ($(this).scrollTop() >= $(this).height()-$(window).height())
        {
            _this.scrollStop(this);
            return;
        }
        _this.scrollDown(this, 1);
    });
}
c_osulist.prototype.refreshView = function($o) {
    $o.find('.article-button').hover(function() {
        $(this).stop(true, false).animate({right: -5}, 200, "swing");
    }, function() {
        $(this).stop(true, false).animate({right: $(this).attr('data-r')}, 200, "swing");
    });
    $o.find('.article-button').each(function() {
        $(this).css({right: $(this).attr('data-r')});
    });
};
c_osulist.prototype.calcPosxArray = function(scrHeight) {
    console.log('scrHeight = ' + scrHeight.toString());
    var ellipseW = 80, ellipseH = scrHeight;
    var t_step = 0.001;
    var posx = [];
    /*
            t step: t_step;
            y = h * sin t
            x = w * cos t
            (-pi/2 <= t <= pi/2)
    */
    for (var t = -Math.PI/2; t <= Math.PI/2; t += t_step)
    {
        var rel_h = parseInt(ellipseH / 2 * Math.sin(t));
        posx[rel_h + parseInt(scrHeight / 2)] = 10 + ellipseW * Math.cos(t);
    }
    this.posx = posx;
}