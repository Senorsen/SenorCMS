
function c_osulist($list_obj)
{
    this.scrHeight = $(window).height();
    this.calcPosxArray(this.scrHeight);
    this.$list_obj = $list_obj;
    this.initListLayer();
    this.refreshView(this.$list_obj);
};
c_osulist.prototype = {
    is_on_scroll: 0,
    refresh_view_intvid: -1
};
c_osulist.prototype.initListLayer = function() {
    var _this = this;
    this.$list_obj.on('mousemove', function(e) {
        var rel_top = e.pageY-$('body').scrollTop()
        //console.log(e.pageX+' '+rel_top);
        if (rel_top <= $(window).height() * 0.2) _this.scrollUp(this);
        else if (rel_top >=  $(window).height() * 0.8) _this.scrollDown(this);
        else _this.scrollStop(this);
    }).mouseleave(function() {
        _this.scrollStop(this);
    }).mousewheel(function(event, delta, deltaX, deltaY) {
        //console.log(delta, deltaX, deltaY);
        $(this).scrollTop($(this).scrollTop() - deltaY * 50);
    });
    this.$list_obj.find('.article-button').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
    });
    this.arrangePosx(this.$list_obj.find('.article-button'));
}
c_osulist.prototype.scrollStop = function(o) {
    console.log('scrollStop');
    this.is_on_scroll = 0;
    $(o).stop(true, false);
}
c_osulist.prototype.scrollUp = function(o, _innercall) {
    var _this = this;
    if (this.is_on_scroll && !_innercall) return;
    //console.log('scrollUp');
    this.is_on_scroll = -1;
    var $ab_o = $(o).find('.article-button');
    $(o).animate({scrollTop: '-=10px'}, 80, "linear", function() {
        _this.arrangePosx($ab_o);
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
    //console.log('scrollDown');
    this.is_on_scroll = 1;
    var $ab_o = $(o).find('.article-button');
    $(o).animate({scrollTop: '+=10px'}, 80, "linear", function() {
        _this.arrangePosx($ab_o);
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
        //console.log(this.id+' hover');
        $(this).addClass('hov').stop(true, false).animate({right: -5}, 200, "swing");
        _this.arrangePosx($ab_o);
    }, function() {
        //console.log(this.id+' hout');
        $(this).removeClass('hov');
        _this.arrangePosx($ab_o);
        $(this).stop(true, false).animate({right: _this.posx[$(this).offset().top]}, 150, "swing", function() {
            
            _this.arrangePosx($ab_o);
        });
    });
    if (this.refresh_view_intvid != -1) clearInterval(this.refresh_view_intvid);
    return;
    this.refresh_view_intvid = setInterval(function() {
        $ab_o.each(function() {
            var $t = $(this);
            if (!$t.hasClass('hov') && typeof _this.posx[$t.offset().top] != 'undefined')
            {
                if (parseInt($t.css('right')) == parseInt(_this.posx[$t.offset().top])) return;
                //console.log(this.id+' dong '+parseInt($t.css('right'))+' '+parseInt(_this.posx[$t.offset().top]));
                //console.log($t.offset().top+' '+_this.posx[$t.offset().top]);
                $t.stop(true, false).animate({right: _this.posx[$t.offset().top]}, 200);
            }
        });
    }, 200);
};
c_osulist.prototype.arrangePosx = function($ab_o) {
    var _this = this;
    $ab_o.each(function() {
        var $t = $(this);
        if (!$t.hasClass('hov') && typeof _this.posx[$t.offset().top] != 'undefined')
        {
            if (parseInt($t.css('right')) == parseInt(_this.posx[$t.offset().top])) return;
            //console.log(this.id+' dong '+parseInt($t.css('right'))+' '+parseInt(_this.posx[$t.offset().top]));
            //console.log($t.offset().top+' '+_this.posx[$t.offset().top]);
            $t.stop(true, false).animate({right: _this.posx[$t.offset().top]}, 200);
        }
    });
};
c_osulist.prototype.calcPosxArray = function(scrHeight) {
    console.log('scrHeight = ' + scrHeight.toString());
    var ellipseW = 120, ellipseH = scrHeight * 1.1;
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
        posx[parseInt(scrHeight / 2 - rel_h - scrHeight * 0.05)] = -180 + ellipseW * Math.cos(t);
    }
    this.posx = posx;
};