<?php
require "dbconnect.php";
$json = file_get_contents("php://input"); // json string
$obj = json_decode($json);

try {
    $stmt = $conn->prepare("");
    $stmt->bindParam(":account_id", $obj->account_id);
    $result = $stmt->execute();
    if ($result) {
        $conn = null;
        $stmt = null;
        echo "1";
    } else {
        $conn = null;
        $stmt = null;
        echo "0";
    }
} catch (Exception $e) {
    echo $e;
}
