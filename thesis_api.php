<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }

    require 'dbconnect.php';
    $sql = $conn->prepare("SELECT * FROM thesis_document LEFT JOIN author_thesis ON thesis_document.thesis_id = author_thesis.thesis_id 
    WHERE thesis_document.thesis_id = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 1;
    foreach ($result as $row) {
        $thesis['thai_name'] = $row->thai_name;
        $thesis['english_name'] = $row->english_name;
        $thesis['author_member']["member$i"] = $row->student_id;
        $i++;
    }

    $thesis = json_encode($thesis, JSON_UNESCAPED_UNICODE);

    echo $thesis;
    
?>