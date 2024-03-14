<?php
session_start();
session_unset();
session_destroy();
setcookie('email', '', time() - 3600, '/'); // ทำลาย Cookie
header("Location: /FinalProj/"); // Redirect ไปที่หน้า login
exit();
?>