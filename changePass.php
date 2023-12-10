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
    <form class="container mt-4" id="contact-form" action="hashPass_db.php" method="POST">
        <h1 class="h1 text-center">เปลี่ยนรหัสผ่าน</h1>
        <div class="form-group mb-3">
            <label for="pass">New Password</label>
            <input class="form-control" type="password" name="pass" placeholder="New Password" required>
        </div>
        <div class="form-group mb-3">
            <label for="pass">Confirm Password</label>
            <input class="form-control" type="password" name="confirm" id="confirm" placeholder="Confirm Password" required>
            <div class="form-text">
            </div>
        </div>

        <input type="submit" class="btn btn-primary container-fluid mb-3" value="ยืนยัน" />
    </form>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">

    <script type="text/javascript">
        $(document).ready(function() {
            $('#contact-form').on('submit', function(e) {
                $.ajax({
                    url: 'hashPass_db.php',
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
                        Swal.fire({
                            title: "Error job!",
                            text: "You clicked the button!",
                            icon: "error"
                        });
                    }
                });
                this.reset();
                e.preventDefault();
            });
        });
    </script>
</body>

</html>