<?php
require "dbconnect.php";
$id = $_GET['id'];

$select = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_id = :id");
$select->bindParam(":id", $id);
$select->execute();
$result = $select->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบข้อมูล</title>
    <link rel="icon" type="image/x-icon" href="../img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php require "template/header.php" ?>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>