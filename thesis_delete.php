<?php
    require "dbconnect.php";
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        try{
            $stmt = $conn->prepare("UPDATE thesis_document SET thesis_status = 0 WHERE thesis_id = :thesis_id");
            $stmt->bindParam(":thesis_id", $id);
            $stmt->execute();
            header('location: /FinalProj');
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
?>