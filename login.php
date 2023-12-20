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
        <form class="container mt-4" action="pass.php" method="POST">
            <h1 class="h1 text-center">เข้าสู่ระบบ</h1>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group mb-3">
                <label for="pass">Password</label>
                <input class="form-control" type="password" name="pass" placeholder="Password" required>
                <div class="form-text">
                หากลืมรหัสผ่าน <span><a href="/FinalProj/pass.php">คลิกที่นี่</a></span>
            </div>
            </div>
            
            <input type="submit" class="btn btn-primary container-fluid mb-3" value="เข้าสู่ระบบ" />
        </form>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
