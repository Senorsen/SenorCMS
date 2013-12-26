// Article show
function c_article($article_layer)
{
    this.$article_layer = $article_layer;
    this.articleViewInit();
}

c_article.prototype = {
    controller_callback: function() {}
};
c_article.prototype.articleViewInit = function() {
    $article_layer = this.$article_layer;
    $article_layer.css({right: 450, left: 'auto', width: $article_layer.width()});
    //$article_layer.animate({width: 0});
};
c_article.prototype.showArticle = function() {
    $article_layer = this.$article_layer;
    $article_layer.css({display: 'block', right: 450, width: 'auto', left: $article_layer.offset().left});
    $article_layer.stop(true, false).animate({left: 50, opacity: 1}, 200, "linear", function() {
        //$article_layer.css({height: 'auto'}).animate({bottom: 30});
    });
    
};
c_article.prototype.prepareArticle = function(obj) {
    $article_layer = this.$article_layer;
    var id = obj.id, title = obj.title, author = obj.author, category = obj.category, date = obj.pubdate;
    console.log(obj);
    with ($article_layer)
    {
        children('.article-title').text(title);
        children('.article-author').text(author);
        children('.article-date').text(date);
        children('.article-category').text(category);
    }
    // let's get article!
    
    $article_layer.css({
        display: 'block',
        right: 450,
        left: 'auto',
        width: $article_layer.width(),
        //bottom: 'auto',
        //height: $article_layer.height()
    });
    $article_layer.animate({width: 0, opacity: 0}, 500, "swing", function() {
        //$article_layer.animate({height: 85});
    });
};
c_article.prototype.getCallback = function(action) {
    eval('this.'+action+'Article = this.'+action+'Article.bind(this);');
    return {action: action, callback: eval('this.'+action+'Article')};
};
