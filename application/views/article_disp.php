    <div id="article-layer">
        <div id="article-title"><?php echo htmlspecialchars($title);?></div>
        <span id="article-pubdate">发布时间：<?php echo $pubdate;?></span>
        <span id="article-author">作者： <?php echo htmlspecialchars($author);?></span>
        <span id="article-category">分类： <?php echo htmlspecialchars($category);?></span>
        <div id="article-content">
            <?php echo $content;?>
        </div>
    </div>