    <div id="list-layer" class="list-layer">
        <div id="list-layer-i" class="list-layer-i">
<?php
    foreach ($list as $value)
    {
?>
        <div id="list-article-<?php echo $value->id;?>" class="article-button" data-r="-<?php echo 50+abs(53-10*$value->id);?>px">
            <span class="a-layer font-hei"><a href="<?php echo base_url();?>article/disp/<?php echo $value->id;?>" id="article-a-<?php echo $value->id;?>" class="article-a-button font-hei" target="_blank"><?php echo htmlspecialchars($value->title);?></a></span>
            <span class="author-layer font-hei"><?php echo htmlspecialchars($value->author);?></span>
            <!--<span><?php echo $value->pubdate;?></span>-->
            <!--<td><?php echo $value->sort;?></td>-->
            <span class="cat-layer font-hei"><?php echo $value->full_name?></span>
        </div>
<?php
    }
?>
        </div>
    </div>
<!-- data start -->
<script language="javascript">
    var PageData = <?php echo json_encode($list);?>;
</script>
