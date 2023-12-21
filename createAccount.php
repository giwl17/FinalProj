<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

// $mysqli = require __DIR__ . "/database.php";
require_once 'dbconnect.php';

$sql = "SELECT * FROM account
        WHERE reset_token_hash = :token_hash";

$select = $conn->prepare($sql);

$select->bindParam("token_hash", $token_hash);
$select->bindParam("name", $_GET['name']);
$select->bindParam("lastname", $_GET['lastname']);

$select->execute();

// $result = $stmt->get_result();
$user = $select->fetch(PDO::FETCH_ASSOC);
// $user = $result->fetch_assoc();



if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require 'template/header_login.php'; ?>
        <form class="container mt-4" action="login.php" method="POST">
            <h1 class="h1 text-center">สร้างรหัสผ่าน</h1>
            <div class="form-group mb-3">
                สวัสดีคุณ <?php echo "$name&nbsp;&nbsp;$lastname";?>
                
            </div>
            <div class="form-group mb-3">
                <label for="password">รหัสผ่าน</label>
                <input class="form-control" type="password" name="password" placeholder="กรุณาใส่รหัสผ่าน" required>
            </div>
            <div class="form-group mb-3">
                <label for="pass">รหัสผ่านอีกครั้ง</label>
                <input class="form-control" type="password" name="checkPassword" placeholder="ใส่รหัสผ่านอีกครั้ง" required>
                
            </div>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="submit" class="btn btn-primary container-fluid mb-3" value="สร้างบัญชีผู้ใช้งาน" />
        </form>
        <script></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>