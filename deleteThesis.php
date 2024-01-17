<?php
require "dbconnect.php";
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST['id'];
    echo $id;
    try{
    //delete member
    $stmt = $conn->prepare("DELETE FROM author_thesis WHERE thesis_id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    //delete thesis
    $stmt = $conn->prepare("DELETE FROM thesis_document WHERE thesis_id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    } catch (PDOException $e) {
        $e->getMessage();
    }
}
