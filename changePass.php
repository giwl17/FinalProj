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
        <form class="container mt-4" action="resetPassword.php" method="POST">
            <h1 class="h1 text-center">เปลี่ยนรหัสผ่าน</h1>
            <div class="form-group mb-3">
                <label for="email">New Password</label>
                <input class="form-control" type="password" name="pass" placeholder="New Password" required>
            </div>
            <div class="form-group mb-3">
                <label for="pass">Confirm Password</label>
                <input class="form-control" type="password" name="confirm" placeholder="Confirm Password" required>
                <div class="form-text">
            </div>
            </div>
            
            <input type="submit" class="btn btn-primary container-fluid mb-3" value="ยืนยัน" />
        </form>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
