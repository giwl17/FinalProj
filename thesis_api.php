<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'dbconnect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

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
} else {
    $sql = $conn->prepare("SELECT * FROM thesis_document ORDER BY thesis_id ASC");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 1;
    $index = 0;
    // print_r($result);
    foreach ($result as $row) {
        $select_mem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
        $select_mem->bindParam(":thesis_id", $row->thesis_id);
        $select_mem->execute();
        $result_mem = $select_mem->fetchAll(PDO::FETCH_OBJ);
        $thesis[$index]['thai_name'] = $row->thai_name;
        $thesis[$index]['english_name'] = $row->english_name;
        foreach($result_mem as $mem) {
            $thesis[$index]['author_member']["member$i"]["student_id"] = $mem->student_id;
            $thesis[$index]['author_member']["member$i"]["name"] = $mem->name;
            $thesis[$index]['author_member']["member$i"]["lastname"] = $mem->lastname;
            $i++;
        }
        $i = 1;
        $index++;
    }

    $thesis = json_encode($thesis, JSON_UNESCAPED_UNICODE);

    echo $thesis;
}
