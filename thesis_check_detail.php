<?php
    require "dbconnect.php";
    $id = $_GET['id'];
    echo $id;
    

    $select = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_id = ?");
    $select->execute([$id]);
    $result = $select->fetchAll();
    foreach($result as $row) {
        echo $row['thai_name'];
        echo $row['english_name'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบข้อมูล</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>