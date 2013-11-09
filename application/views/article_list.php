    <table>
<?php
    foreach ($list as $value)
    {
?>
        <tr>
            <td><?php echo $value->id;?></td>
            <td><?php echo htmlspecialchars($value->title);?></td>
            <td><?php echo htmlspecialchars($value->author);?></td>
            <td><?php echo $value->pubdate;?></td>
            <td><?php echo $value->sort;?></td>
        </tr>
<?php
    }
?>
    </table>