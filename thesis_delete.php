<?php

require "dbconnect.php";

date_default_timezone_set("Asia/Bangkok");
$currentTime = date_default_timezone_get();
$datetime = date('Y-m-d H:i:s');


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("UPDATE thesis_document SET thesis_status = 0, dateTime_deleted = :dateTime_deleted WHERE thesis_id = :thesis_id");
        $stmt->bindParam(":thesis_id", $id);
        $stmt->bindParam(":dateTime_deleted", $datetime);
        $stmt->execute();
        header('location: /FinalProj');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = [];
        foreach ($data as $i) {
            $data_ex  = explode("_", $i);
            $data_ex = array_pop($data_ex);
            array_push($id, $data_ex);
        }
        $sql = "UPDATE thesis_document SET thesis_status = 0, dateTime_deleted = :dateTime_deleted  WHERE thesis_id = $id[0]";
        for ($i = 1; $i < count($id); $i++) {
            $sql .= " OR thesis_id = $id[$i]";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":dateTime_deleted", $datetime);
        $result = $stmt->execute();
        echo "1";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // echo "datetime : " .$datetime;
}
