    <table>
<?php
    foreach ($list as $value)
    {
?>
        <tr>
            <td><?php echo $value->id;?></td>
            <td><a href="<?php echo base_url();?>article/disp/<?php echo $value->id;?>" target="_blank"><?php echo htmlspecialchars($value->title);?></td></td>
            <td><?php echo htmlspecialchars($value->author);?></td>
            <td><?php echo $value->pubdate;?></td>
            <td><?php echo $value->sort;?></td>
            <td><?php echo $value->full_name?></td>
        </tr>
<?php
    }
?>
    </table>