<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Page</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require 'template/header_login.php'; ?>
    <form id="emailForm" method="POST" accept="send_mail.php" class="container mt-4">
        <h1 class="h1 text-center">ลืมรหัสผ่าน</h1>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input id="email" class="form-control" type="email" name="email" placeholder="Enter email" required>
        </div>
        <input type="submit" class="btn btn-primary container-fluid" value="ส่งลิงก์สำหรับเปลี่ยนรหัสผ่าน" />
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
                            title: "ส่งอีเมลสำเร็จ",
                            text: "ส่งลิงก์สำหรับเปลี่ยนรหัสผ่านไปยังอีเมลเรียบร้อย",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "ส่งอีเมลไม่สำเร็จ",
                            text: "ไม่มีพบผู้ใช้งาน",
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
    <!--   <script>
      document.getElementById("emailForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Client-side form validation (example)
    var emailInput = document.getElementById("email");
    if (!isValidEmail(emailInput.value)) {
        Swal.fire({
            title: "Invalid Email",
            text: "Please enter a valid email address.",
            icon: "error"
        });
        return;
    }

    var formData = new FormData(this);

    // Show loading indicator
    Swal.fire({
        title: "Sending Email",
        text: "Please wait...",
        icon: "info",
        showConfirmButton: false
    });

    fetch("send_email.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'exists') {
            // Delay before showing success message
            setTimeout(() => {
                Swal.fire({
                    title: "Email Sent Successfully",
                    text: "Check your inbox for the reset link.",
                    icon: "success"
                });
            }, 500); // Delay in milliseconds
        } else {
            Swal.fire({
                title: "Email Not Sent",
                text: "User not found or an error occurred.",
                icon: "error"
            });
        }
    })
    .catch(error => {
        console.error("An error occurred:", error);
        Swal.fire({
            title: "Error!",
            text: "An error occurred. Please try again later.",
            icon: "error"
        });
    })
    .finally(() => {
        // Reset the form
        document.getElementById("emailForm").reset();
    });
});

function isValidEmail(email) {
    // Implement your email validation logic here (e.g., regex check)
    // Return true if the email is valid, false otherwise
    return true;
}

    </script> -->
</body>

</html>