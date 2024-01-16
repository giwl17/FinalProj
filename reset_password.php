<?php
require_once 'dbconnect.php';

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM account WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()");
    $stmt->execute([$token]);
    $password_reset = $stmt->fetch();

    if ($password_reset) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];
            $email = $password_reset['email'];

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE account SET password = ? WHERE email = ?");
            $stmt->execute([$hashed_password, $email]);

            $stmt = $conn->prepare("UPDATE account SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
            $stmt->execute([NULL, NULL, $email]);

            $response = [
                'status' => 'success',
                'message' => 'Password updated successfully. You can now login with your new password.'
            ];
            echo json_encode($response);
            exit();
        }
    } else {
        header('Location: 404.php');
        $response = [
            'status' => 'error',
            'message' => 'Invalid or expired token.'
        ];
        echo json_encode($response);
        exit();
    }
} else {
    echo "Token not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password page</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
</head>

<body>
    <?php require 'template/header_login.php'; ?>
    <form class="container mt-4" id="resetForm" method="post" onsubmit="return validateForm()">
        <h1 class="h1 text-center">เปลี่ยนรหัสผ่าน</h1>
        <div class="form-group mb-3">
            <label for="pass">New Password</label>
            <input type="password" id="pass" name="pass" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="pass">Confirm Password</label>
            <input type="password" id="new_password" name="new_password" class="form-control" required>
            <div class="form-text">
            </div>
        </div>
        <input type="submit" class="btn btn-primary container-fluid mb-3" value="ยืนยัน" />
    </form>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#resetForm').on('submit', function(e) {
                var password = $('#pass').val();
                var new_password = $('#new_password').val();

                if (password !== new_password) {
                    Swal.fire({
                        title: "Error!",
                        text: "Passwords do not match.",
                        icon: "error"
                    });
                    e.preventDefault(); // Prevent form submission
                } else {
                    var formData = new FormData(this);
                    fetch("reset_password.php?token=<?php echo $token; ?>", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href = "login.php"; // Redirect to login page
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while processing your request.',
                                icon: 'error'
                            });
                        });
                    e.preventDefault(); // Prevent default form submission
                }
            });
        });
    </script>
    <?php session_destroy(); ?>
</body>

</html>