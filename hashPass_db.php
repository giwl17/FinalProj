<?php
require_once 'dbconnect.php';

session_start();
date_default_timezone_set('asia/bangkok');

if (isset($_POST["confirm"])) {
    // $pass = $_POST["pass"];
    $confirm = $_POST["confirm"];
    $hashPass = password_hash($confirm, PASSWORD_DEFAULT);

    try {
        $update =  $conn->prepare("UPDATE account SET password = '$hashPass' WHERE email = 'tanwa@gmail.com'");
        // $update = $conn->prepare("UPDATE account SET password = '$confirm' WHERE email = 'tanwa@gmail.com'");

        $result = $update->execute();
        // $result = mysqli_query($conn, $update);
    } catch (PDOException $e) {
        echo $e;
    }


    $conn = null;
}

session_destroy();
