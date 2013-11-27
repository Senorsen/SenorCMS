    <div id="top-layer">
        
    </div>
    <div id="list-layer" class="list-layer">
        <div class="list-layer-i">
<!-- Article inner listlayer content start - Senorsen capture -->
<?php
    foreach ($list as $value)
    {
?>
        <div id="list-article-<?php echo $value->id;?>" class="article-button" style="right: -200px" x-dataarea-json="<?php echo urlencode(json_encode($value));?>">
            <span class="a-layer font-hei"><a href="<?php echo base_url();?>article/disp/<?php echo $value->id;?>" id="article-a-<?php echo $value->id;?>" class="article-a-button font-hei" target="_blank"><?php echo htmlspecialchars($value->title);?></a></span>
            <span class="author-layer font-hei"><?php echo htmlspecialchars($value->author_name);?></span>
            <!--<span><?php echo $value->pubdate;?></span>-->
            <!--<td><?php echo $value->sort;?></td>-->
            <span class="cat-layer font-hei"><?php echo $value->full_name?></span>
        </div>
<?php
    }
?>
<!-- Article inner listlayer content end - Senorsen capture -->
        </div>
        <div class="list-btn list-btn-up" data-scroll-dir="-1"></div>
        <div class="list-btn list-btn-down" data-scroll-dir="1"></div>
    </div>
    <div id="article-layer" class="article-layer">
        <div id="article-layer-i" class="article-layer-i">
        </div>
    </div>
    <div id="preview-layer" class="preview-layer">
        <div id="preview-layer-i" class="preview-layer-i">
        </div>
    </div>
    