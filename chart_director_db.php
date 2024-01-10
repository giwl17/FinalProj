<?php
header('Content-Type: application/json; charset=utf-8');
include "dbconnect.php";

$year = $_GET['y'] ?? '';

if ($year !== '') {
    if ($year == 'all') {
        $select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document GROUP BY prefix_advisor, name_advisor, surname_advisor");
    } else {
        $select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document WHERE approval_year = :year
        GROUP BY prefix_advisor, name_advisor, surname_advisor");
        $select->bindParam(":year", $year);
    }
} else {
    $select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document GROUP BY prefix_advisor, name_advisor, surname_advisor");
}
$select->execute();
$result = $select->fetchAll();
if ($select->rowCount() > 0) {
    foreach ($result as $row) {
        $dataPoints[] =  array("y" => $row['count'], "label" => $row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor']);
    }
    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
} else {
    echo json_encode(array("isFound" => "no"));
}
