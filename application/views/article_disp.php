<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($title);?></title>
<?php echo $static->header;?>

</head>

<body>
    <div id="article-layer">
        <div id="article-title"><?php echo htmlspecialchars($title);?></div>
        <span id="article-pubdate">发布时间：<?php echo $pubdate;?></span>
        <span id="article-author">作者： <?php echo htmlspecialchars($author);?></span>
        <div id="article-content">
            <?php echo $content;?>
        </div>
    </div>
<?php echo $static->footer;?>

</body>
</html>