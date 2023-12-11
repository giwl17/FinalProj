<?php
require_once 'dbconnect.php';

session_start();
date_default_timezone_set('asia/bangkok');

if (isset($_POST["confirm"])) {

    $pass = $_POST["confirm"];
    $hashPass = password_hash($pass, PASSWORD_DEFAULT);
    try {
        $update =  $conn->prepare("UPDATE account SET password = '$hashPass' WHERE email = 'tanwa@gmail.com'");
        // $update = "UPDATE account SET password = '$pass' WHERE email = 'tanwa@gmail.com'";

        $result = $update->execute();
        // $result = mysqli_query($conn, $update);
    } catch (PDOException $e) {
        echo $e;
    }
    $conn = null;
}

session_destroy();
