<?php
    require "dbconnect.php";
    if($_GET['id']) {
        $id = $_GET['id'];

        try{
            $stmt = $conn->prepare("UPDATE thesis_document SET thesis_status = 0 WHERE thesis_id = :thesis_id");
            $stmt->bindParam(":thesis_id", $id);
            $stmt->execute();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
?>