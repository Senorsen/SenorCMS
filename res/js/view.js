
function c_view()
{
    this.osulist = new c_osulist($('#list-layer'));
    this.article = new c_article($('#article-layer'));
    this.osulist.setCallback(this.article.getCallback('prepare'));
    this.osulist.setCallback(this.article.getCallback('show'));
};
c_view.prototype = {
    
};