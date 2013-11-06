<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $title;?></title>
<?php echo $static->header;?>

</head>

<body>
    <table>
<?php
    foreach ($list as $value)
    {
?>
        <tr>
            <td><?php echo $value->id;?></td>
            <td><?php echo htmlspecialchars($value->title);?></td>
            <td><?php echo htmlspecialchars($value->author);?></td>
            <td><?php echo $value->sort;?></td>
        </tr>
<?php
    }
?>
    </table>
<?php echo $static->footer;?>

</body>
</html>