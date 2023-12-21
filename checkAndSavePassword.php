<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

// $mysqli = require __DIR__ . "/database.php";
require_once 'dbconnect.php';

$sql = "SELECT * FROM account
        WHERE reset_token_hash = ?";

$stmt = $conn->prepare($sql);

$stmt->bindparam("s", $token_hash);

$stmt->execute();

// $result = $stmt->get_result();

$user = $select->fetch(PDO::FETCH_ASSOC);


if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["checkPassword"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE account
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bindparam("ss", $password_hash, $user["id"]);

$stmt->execute();

echo "<script type=\"text/javascript\">
alert('Password updated. You can now login');
</script>.";