<?php
require "dbconnect.php";
$json = file_get_contents("php://input"); // json string
$obj = json_decode($json);

try {
    $stmt = $conn->prepare("UPDATE account SET studentId = :student_id, prefix = :prefix, name = :firstname, lastname = :lastname, email = :email WHERE account_id = :account_id");
    $stmt->bindParam(":student_id", $obj->student_id);
    $stmt->bindParam(":prefix", $obj->prefix);
    $stmt->bindParam(":firstname", $obj->firstname);
    $stmt->bindParam(":lastname", $obj->lastname);
    $stmt->bindParam(":email", $obj->email);
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
