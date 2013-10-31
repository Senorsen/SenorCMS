<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $title;?></title>
</head>

<body>
以下为表格
<table>
<?php
foreach ($list as $key => $value)
{
    ?>
    <tr>
        <td><?php echo $value->id;?></td>
        <td><?php echo $value->title;?></td>
        <td><?php echo $value->author;?></td>
        <td><?php echo $value->sort;?></td>
    </tr>
    <?
}
?>
</table>
</body>
</html>