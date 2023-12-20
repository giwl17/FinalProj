<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to autoload.php of PHPMailer
 // File containing database connection
require_once 'dbconnect.php';
// require 'db_connection.php';

date_default_timezone_set("Asia/Bangkok");

// Generate a random token
$token = bin2hex(random_bytes(32)); // Generating a 64-character token
$hashed_token = hash("sha256", $token);
// $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the 'users' table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Store the token, email, and expiration time in the 'password_reset' table
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expiration) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expiry]);

        // Construct the reset link with the token
        $reset_link = "https://rmuttcpethesis.com/FinalProj/reset_password.php?token=$token";

        // Send email to the user containing the reset link using PHPMailer
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
