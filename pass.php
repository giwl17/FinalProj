<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Page</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require 'template/header_login.php'; ?>
    <form id="emailForm" method="POST" class="container mt-4">
        <h1 class="h1 text-center">ลืมรหัสผ่าน</h1>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" placeholder="Enter email" required>
        </div>
        <input type="submit" class="btn btn-primary container-fluid" value="เข้าสู่ระบบ" />
    </form>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById("emailForm").addEventListener("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            fetch("send_email.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'exists') {
                        Swal.fire({
                            title: "Email Exists!",
                            text: "The email exists in the database.",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Email Not Found!",
                            text: "The email does not exist in the database.",
                            icon: "error"
                        });
                    }
                    document.getElementById("emailForm").reset();
                })
                .catch(error => {
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred.",
                        icon: "error"
                    });
                });
        });
    </script>
</body>

</html>