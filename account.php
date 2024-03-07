<?php
require "dbconnect.php";
$json = file_get_contents("php://input"); // json string
$obj = json_decode($json);
$stmt = $conn->prepare("SELECT * FROM account WHERE account_id = :id");
$stmt->bindParam(":id", $obj->id);
$stmt->execute();
$row = $stmt->fetch();
$array = array(
    "account_id" => $row['account_id'],
    "studentId" => $row['studentId'],
    "prefix" => $row['prefix'],
    "name" => $row['name'],
    "lastname" => $row['lastname'],
    "email" => $row['email']
);

echo json_encode($array);