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
    <!-- <form class="container mt-4" action="setMail.php" method="POST"> -->
    <form action="setMail.php" method="POST" class="container mt-4" id="contact-form">
        <h1 class="h1 text-center">ลืมรหัสผ่าน</h1>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" placeholder="Enter email" required>
        </div>
        <input type="submit" class="btn btn-primary container-fluid" value="เข้าสู่ระบบ" />
    </form>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#contact-form').on('submit', function(e) { //Don't foget to change the id form
                $.ajax({
                    url: 'setMail.php', //===PHP file name====
                    data: $(this).serialize(),
                    type: 'POST',
                    success: function(data) {
                        console.log(data);
                        //Success Message == 'Title', 'Message body', Last one leave as it is
                        // swal("Success!", "Message sent Mail", "success");
                        Swal.fire({
                            title: "Alert!",
                            text: "Your message here",
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "login.php";
                            }
                        });
                    },
                    error: function(data) {
                        //Error Message == 'Title', 'Message body', Last one leave as it is
                        swal("Oops...", "Something went wrong :(", "error");
                    }
                });
                this.reset();
                e.preventDefault(); //This is to Avoid Page Refresh and Fire the Event "Click"
            });
        });
    </script>
</body>

</html>