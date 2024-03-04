<?php
header('Content-Type: application/json; charset=utf-8');
include "dbconnect.php";

$year = $_GET['y'] ?? '';

if ($year !== '') {
    if ($year == 'all') {
        $select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document GROUP BY prefix_advisor, name_advisor, surname_advisor");
        $select_count = $conn->prepare("SELECT COUNT(approval_year) as count, approval_year FROM thesis_document  WHERE approval_status = 1 GROUP BY approval_year");
    } else {
        $select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document WHERE approval_year = :year
        GROUP BY prefix_advisor, name_advisor, surname_advisor");
        $select->bindParam(":year", $year);
        $select_count = $conn->prepare("SELECT COUNT(approval_year) as count, approval_year FROM thesis_document  WHERE approval_status = 1 AND approval_year = :year GROUP BY approval_year");
        $select_count->bindParam(":year", $year);
    }
} else {
    $select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document GROUP BY prefix_advisor, name_advisor, surname_advisor");
    $select_count = $conn->prepare("SELECT COUNT(approval_year) as count, approval_year FROM thesis_document  WHERE approval_status = 1 GROUP BY approval_year");
}
$select->execute();
$result = $select->fetchAll();
if ($select->rowCount() > 0) {
    foreach ($result as $row) {
        $dataPoints[] =  array("data1" => ["y" => $row['count'], "label" => $row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor']]);
    }
    $select_count->execute();
    $rows = $select_count->fetchAll();
    foreach ($rows as $row) {
        $count_thesis = $row['count'];
        $approval_year = $row['approval_year'];
        $dataPoints[] = array("data2" => ["x" => $approval_year, "y" => $count_thesis]);
    }

    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
} else {
    echo json_encode(array("isFound" => "no"));
}

// $select_count->execute();
// $rows = $select_count->fetchAll();
// foreach ($rows as $row) {
//     $count_thesis = $row['count'];
//     $approval_year = $row['approval_year'];
//     $dataPoints_count[] = array("x" => $approval_year, "y" => $count_thesis);
// }
// echo json_encode($dataPoints_count, JSON_NUMERIC_CHECK);
