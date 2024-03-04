<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password Page</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php require 'template/header.php'; ?>
    <div class="container mt-5">
        <h1 class="h1 text-center">เปลี่ยนรหัสผ่าน</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form class="container mt-4" id="resetForm" method="post">
                            <div class="form-group mb-3">
                                <label for="pass">New Password</label>
                                <input type="password" id="pass" name="pass" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="pass">Confirm Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                                <div class="form-text"></div>
                            </div>
                            <input type="submit" class="btn btn-primary container-fluid mb-3" value="ยืนยัน" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#resetForm').on('submit', function (e) {
                var password = $('#pass').val();
                var new_password = $('#new_password').val();

                var isValidPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{10,}$/;

                if (password !== new_password) {
                    Swal.fire({
                        title: "รหัสผ่านไม่ตรงกัน",
                        text: "กรุณาตรวจสอบว่ารหัสผ่านทั้งสองตัวที่คุณป้อนเป็นตัวเลขและตัวอักษรที่ผสมผสานกันอย่างถูกต้องและมีความยาวไม่น้อยกว่า 10 ตัวอักษร และให้แน่ใจว่าไม่มีการพิมพ์ผิดหรือเว้นวรรค",
                        icon: "error"
                    });
                    e.preventDefault(); // Prevent form submission
                } else if (!isValidPassword.test(password)) {
                    Swal.fire({
                        title: "รหัสผ่านไม่ตรงตามข้อกำหนด",
                        text: "กรุณากำหนดรหัสผ่านที่มีการผสมผสานระหว่างตัวอักษรใหญ่, ตัวอักษรเล็ก, และตัวเลข โดยมีความยาวไม่น้อยกว่า 10 ตัวอักษร",
                        icon: "error"
                    });
                    e.preventDefault(); // Prevent form submission
                } else {
                    var formData = new FormData(this);
                    fetch("change_password_db.php", {
                        method: "POST",
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'เสร็จสิ้น',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href = "/FinalProj/"; // Redirect to login page
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด',
                                text: 'เกิดข้อผิดพลาดขณะประมวลผลคำขอของคุณ',
                                icon: 'error'
                            });
                        });
                    e.preventDefault(); // Prevent default form submission
                }
            });
        });
    </script>
</body>

</html>
