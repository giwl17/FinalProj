<?php
session_start();
session_unset();
session_destroy();
setcookie('email', '', time() - 3600, '/'); // ทำลาย Cookie
header("Location: login.php"); // Redirect ไปที่หน้า login
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <script>
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Logged Out!',
                    'You have been logged out.',
                    'success'
                ).then(function() {
                    window.location = 'login.html'; // หรือไปยังหน้าที่ต้องการหลังจาก logout
                });
            }
        });
    </script>
</body>
</html> -->
