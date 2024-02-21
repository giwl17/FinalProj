<?php
require_once 'dbconnect.php';

if(isset($_GET["token"])){
     $token = $_GET["token"];

try {
$sql = "SELECT * FROM account WHERE reset_token_hash = :token";
$select = $conn->prepare($sql);
$select->bindParam("token", $token,PDO::PARAM_STR);
$select->execute();

$user = $select->fetch(PDO::FETCH_ASSOC);

if($user){
    $name = $user['name'];
    $lastname = $user['lastname'];
    
}else{
    die("cant select");
}

if ($user === null) {
    die("token incorrect");
}

if (strtotime($user['reset_token_expires_at']) <= time()) {
    die("token has expired");
}
} catch (PDOException $e) {
    echo''. $e->getMessage() .'';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $password = $_POST["password"];
    $checkPassword = $_POST["checkPassword"];   
    $token = $_GET["token"];
$token_hash = password_hash($token, PASSWORD_DEFAULT);
$password_hash= password_hash($password, PASSWORD_DEFAULT);


if (strnatcmp($password,$checkPassword)==0) {
    try {
        $sql = "UPDATE account SET password = :password_hash , reset_token_hash =NULL,reset_token_expires_at =NULL
                WHERE reset_token_hash = :token";
        
        $select = $conn->prepare($sql);
        $select->bindParam("password_hash", $password_hash,PDO::PARAM_STR);
        $select->bindParam("token", $token,PDO::PARAM_STR);
        
        
        $select->execute();
        
        // echo"update password";
        echo '<script>alert("update password complete");</script>';
        echo '<script>window.location.href = "login.php";</script>';
       
        } catch (PDOException $e) {
            echo''. $e->getMessage() .'';
            }
}else{
    die('Your confirm is not correct');
}
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
<script>
        // Check if the page is being loaded from the cache (user clicked the back button)
        window.onpageshow = function(event) {
            if (event.persisted) {
                // Force a page refresh
                location.reload();
            }
        };
    </script>
    <?php require 'template/header_login.php'; ?>
        <form class="container mt-4"  method="POST">
            <h1 class="h1 text-center">สร้างรหัสผ่าน</h1>
            <div class="form-group mb-3">
                สวัสดีคุณ <?php echo $name . '&nbsp;&nbsp;' . $lastname; ?>
                
            </div>
            <div class="form-group mb-3">
                <label for="password">รหัสผ่าน</label>
                <input class="form-control" type="password" name="password" id="password" placeholder="กรุณาใส่รหัสผ่าน" required>
            </div>
            <div class="form-group mb-3">
                <label for="pass">รหัสผ่านอีกครั้ง</label>
                <input class="form-control" type="password" name="checkPassword" id="checkPassword" placeholder="ใส่รหัสผ่านอีกครั้ง" required>
                
            </div>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="submit" class="btn btn-primary container-fluid mb-3" value="สร้างบัญชีผู้ใช้งาน" />
        </form>
        <script></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>