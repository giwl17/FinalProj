<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login page</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <?php
    require_once 'dbconnect.php';

    if (isset($_GET["token"])) {
        $token = $_GET["token"];

        try {
            $sql = "SELECT * FROM account WHERE reset_token_hash = :token";
            $select = $conn->prepare($sql);
            $select->bindParam("token", $token, PDO::PARAM_STR);
            $select->execute();

            $user = $select->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $name = $user['name'];
                $lastname = $user['lastname'];
            } else {
                echo '<script>window.location.href = "404.php";</script>';
            }

            if ($user === null) {
                echo '<script>window.location.href = "404.php";</script>';
            }

            if (strtotime($user['reset_token_expires_at']) <= time()) {
                echo '<script>window.location.href = "404.php";</script>';
            }
        } catch (PDOException $e) {
            echo '' . $e->getMessage() . '';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST["password"];
        $checkPassword = $_POST["checkPassword"];
        $token = $_GET["token"];
        $token_hash = password_hash($token, PASSWORD_DEFAULT);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if (strnatcmp($password, $checkPassword) == 0) {
            try {
                $sql = "UPDATE account SET password = :password_hash , reset_token_hash =NULL,reset_token_expires_at = NULL
                WHERE reset_token_hash = :token";

                $select = $conn->prepare($sql);
                $select->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
                $select->bindParam("token", $token, PDO::PARAM_STR);

                $select->execute();
                echo <<<HTML
                    <script>
                        Swal.fire({
                            title: "ตั้งรหัสผ่านเรียบร้อยแล้ว",
                            icon: "success"
                        }).then(() => {
                            window.location.href = "login.php"
                        })
                    </script>
                HTML; 
                // echo"update password";   
                // echo `<script>
                // Swal.fire({
                // title: "เปลี่ยนรหัสผ่านเสร็จสิ้น",
                // icon: "success",
                // });
                // </script>`;
                // echo '<script>window.location.href = "login.php";</script>';
            } catch (PDOException $e) {
                echo '' . $e->getMessage() . '';
            }
        } else {
            echo '<script>
                Swal.fire({
                title: "รหัสยืนยันไม่ถูกต้อง",
                icon: "error"
                });
            </script>';
        }
    }
    ?>
   <?php require 'template/header_login.php'; ?>
<form class="container mt-4" method="POST" id="resetForm">
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
    <input type="submit" class="btn btn-primary container-fluid mb-3" name="submitPass" value="ตั้งรหัสผ่าน" />
</form>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function () {
        $("#resetForm").submit(function (event) {
            // event.preventDefault(); // Prevent the form from submitting  

            // Your validation code
            var password = $("#password").val();
            var checkPassword = $("#checkPassword").val();
            var isValidPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{10,}$/;

            if (!isValidPassword.test(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ถูกต้อง',
                    text: 'รหัสผ่านต้องประกอบด้วย ตัวพิมพ์เล็ก ตัวพิมพ์ใหญ่ และตัวเลข และมีความยาวอย่างน้อย 10 ตัวอักษร'
                });
                return false;
            }

            if (password !== checkPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณากรอกรหัสผ่านให้ตรงกัน'
                });
                return false;
            }


            // If validation passes, you can submit the form
            // Uncomment the following line if you want to submit the form after validation
            // $("#resetForm").unbind('submit').submit();
        });
    });
</script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>