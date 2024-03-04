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
} else if (isset($_GET['search']) && isset($_GET['limit'])) {
    $search = $_GET['search'];
    $like = "%" . $search . "%";
    $limit = $_GET['limit'];

    $sql = $conn->prepare("SELECT * FROM thesis_document
    WHERE (thesis_status = 1 AND approval_status = 1)
    AND (thai_name LIKE :like
    OR printed_year LIKE :like
    OR english_name LIKE :like)
    ORDER BY thesis_id DESC
    LIMIT $limit
    ");
    $sql->bindParam(":like", $like);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 0;
    foreach ($result as $row) {
        $thesis[$i]['thesis_id'] = $row->thesis_id;
        $thesis[$i]['thai_name'] = $row->thai_name;
        $thesis[$i]['english_name'] = $row->english_name;
        $thesis[$i]['printed_year'] = $row->printed_year;
        $thesis[$i]['semester'] = $row->semester;
        $thesis[$i]['approval_year'] = $row->approval_year;
        $i++;
    }
    $thesis = json_encode($thesis, JSON_UNESCAPED_UNICODE);
    echo $thesis;
} else if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $like = "%" . $search . "%";

    $sql = $conn->prepare("SELECT * FROM thesis_document
    WHERE thesis_status = 1 
    AND (thai_name LIKE :like
    OR english_name LIKE :like
    OR printed_year LIKE :like)
    ORDER BY thesis_id DESC");
    $sql->bindParam(":like", $like);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 0;
    foreach ($result as $row) {
        $thesis[$i]['thesis_id'] = $row->thesis_id;
        $thesis[$i]['thai_name'] = $row->thai_name;
        $thesis[$i]['english_name'] = $row->english_name;
        $thesis[$i]['printed_year'] = $row->printed_year;
        $thesis[$i]['semester'] = $row->semester;
        $thesis[$i]['approval_year'] = $row->approval_year;
        $i++;
    }
    $thesis = json_encode($thesis, JSON_UNESCAPED_UNICODE);
    echo $thesis;
}  else if(isset($_GET['start']) && isset($_GET['perpage'])) {
    $start = $_GET['start'];
    $perpage = $_GET['perpage'];
    $sql = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 
    ORDER BY thesis_id DESC
    LIMIT {$start} , {$perpage}
    ");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 1;
    $index = 0;
    foreach ($result as $row) {
        $select_mem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
        $select_mem->bindParam(":thesis_id", $row->thesis_id);
        $select_mem->execute();
        $result_mem = $select_mem->fetchAll(PDO::FETCH_OBJ);
        $thesis[$index]['thesis_id'] = $row->thesis_id;
        $thesis[$index]['thai_name'] = $row->thai_name;
        $thesis[$index]['english_name'] = $row->english_name;
        $thesis[$index]['prefix_advisor'] = $row->prefix_advisor;
        $thesis[$index]['name_advisor'] = $row->name_advisor;
        $thesis[$index]['surname_advisor'] = $row->surname_advisor;
        $thesis[$index]['prefix_coAdvisor'] = $row->prefix_coAdvisor;
        $thesis[$index]['name_coAdvisor'] = $row->name_coAdvisor;
        $thesis[$index]['surname_coAdvisor'] = $row->surname_coAdvisor;
        $thesis[$index]['keyword'] = $row->keyword;
        $thesis[$index]['printed_year'] = $row->printed_year;
        foreach ($result_mem as $mem) {
            $thesis[$index]['author_member']["member$i"]["student_id"] = $mem->student_id;
            $thesis[$index]['author_member']["member$i"]["prefix"] = $mem->prefix;
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

else {
    $sql = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY thesis_id DESC");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 1;
    $index = 0;
    foreach ($result as $row) {
        $select_mem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
        $select_mem->bindParam(":thesis_id", $row->thesis_id);
        $select_mem->execute();
        $result_mem = $select_mem->fetchAll(PDO::FETCH_OBJ);
        $thesis[$index]['thesis_id'] = $row->thesis_id;
        $thesis[$index]['thai_name'] = $row->thai_name;
        $thesis[$index]['english_name'] = $row->english_name;
        $thesis[$index]['prefix_advisor'] = $row->prefix_advisor;
        $thesis[$index]['name_advisor'] = $row->name_advisor;
        $thesis[$index]['surname_advisor'] = $row->surname_advisor;
        $thesis[$index]['prefix_coAdvisor'] = $row->prefix_coAdvisor;
        $thesis[$index]['name_coAdvisor'] = $row->name_coAdvisor;
        $thesis[$index]['surname_coAdvisor'] = $row->surname_coAdvisor;
        $thesis[$index]['keyword'] = $row->keyword;
        $thesis[$index]['printed_year'] = $row->printed_year;
        foreach ($result_mem as $mem) {
            $thesis[$index]['author_member']["member$i"]["student_id"] = $mem->student_id;
            $thesis[$index]['author_member']["member$i"]["prefix"] = $mem->prefix;
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
