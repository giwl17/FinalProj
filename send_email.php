<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

require_once 'dbconnect.php';

date_default_timezone_set("Asia/Bangkok");
$token = bin2hex(random_bytes(32)); 
$hashed_token = hash("sha256", $token);
// $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM account WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        /* $stmt = $conn->prepare("INSERT INTO account (email, reset_token_hash, reset_token_expires_at) VALUES (?, ?, ?) ");
        $stmt->execute([$email, $token, $expiry]); */
        $stmt = $conn->prepare("UPDATE account SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
        $stmt->execute([$token, $expiry, $email]);

        $reset_link = "https://rmuttcpethesis.com/FinalProj/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'rmuttcpethesis.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'rmuttcp'; // SMTP username
            $mail->Password = 'xG5qK2sg43'; // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 25;

            //Recipients
            $mail->setFrom("rmuttcp@rmuttcpethesis.com", "Admin");
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body = "To reset your password, <br>
            token : $token <br>
            click on the following link: <a href='$reset_link'>reset_link</a>";

            $mail->send();
            echo 'exists';

            // echo 'Email sent successfully. Check your inbox for the reset link.';
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
        // $_SESSION['email'] = $email;
        // header("Location: reset_password.php?token=$token"); // ส่งค่า token ไปด้วยใน URL
        // exit();
    } else {
        echo 'not_exists';
        // echo 'Email not found in our records.';
    }
}

// session_destroy();
