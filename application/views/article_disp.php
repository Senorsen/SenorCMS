<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=htmlspecialchars($title)?></title>
<?=$static->header?>

</head>

<body>
    <div id="article-layer">
        <div id="article-title"><?=htmlspecialchars($title)?></div>
        <span id="article-pubdate">发布时间：<?=$pubdate?></span>
        <span id="article-author">作者： <?=htmlspecialchars($author)?></span>
        <div id="article-content">
            <?=$content?>
        </div>
    </div>
<?=$static->footer?>

</body>
</html>