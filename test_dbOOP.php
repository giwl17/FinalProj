<?php
require "database.php";
$conn = new Database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $rows = $conn->selectThesisFull();
    // print_r($rows);
    for ($i = 0; $i < count($rows); $i++) {
        echo "thai_name : " . $rows[$i]['thai_name'] . "<br>";
        foreach ($rows[$i]['author_member'] as $key => $value) {
            $name = $rows[$i]['author_member'][$key]['prefix'] . $rows[$i]['author_member'][$key]['name'] . " " . $rows[$i]['author_member'][$key]['lastname'];
            echo $name;
        }
        echo "<br>";
    }
    ?>
</body>

</html>