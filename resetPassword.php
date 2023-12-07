<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Page</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require 'template/header_login.php'; ?>
        <form class="container mt-4" action="" method="POST">
            <h1 class="h1 text-center">ลืมรหัสผ่าน</h1>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" placeholder="Enter email" required>
            </div>
            
            
            <input type="submit" class="btn btn-primary container-fluid" value="เข้าสู่ระบบ" />
        </form>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>