<?php
$prefix = $_POST["prefix"];
$name = $_POST["name"];
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$role =  $_POST["role"];
$download_permissions = $_POST["download_permissions"];
$member_manage_permission = $_POST["member_manage_permission"];
$account_manage_permission = $_POST["account_manage_permission"];
$status = $_POST["status"];



date_default_timezone_set("Asia/Bangkok");

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'dbconnect.php';
// require "mail.php";
try {
    $insert = $conn->prepare("INSERT INTO account (password,prefix,name,lastname,email,role,download_permissions,member_manage_permission,account_manage_permission,status,reset_token_hash,reset_token_expires_at)
        VALUES(:token,:prefix,:name,:lastname,:email,:role,:download_permissions,:member_manage_permission,:account_manage_permission,:status,:reset_token_hash,:reset_token_expires_at)");
    $insert->bindParam("token", $token_hash, PDO::PARAM_STR);
    $insert->bindParam("prefix", $prefix, PDO::PARAM_STR);
    $insert->bindParam("name", $name, PDO::PARAM_STR);
    $insert->bindParam("lastname", $lastname, PDO::PARAM_STR);
    $insert->bindParam("email", $email, PDO::PARAM_STR);
    $insert->bindParam("role", $role);
    $insert->bindParam("download_permissions", $download_permissions);
    $insert->bindParam("member_manage_permission", $member_manage_permission);
    $insert->bindParam("account_manage_permission", $account_manage_permission);
    $insert->bindParam("status", $status);
    $insert->bindParam("reset_token_hash", $token_hash);
    $insert->bindParam("reset_token_expires_at", $expiry);


    $result = $insert->execute();
} catch (PDOException $e) {
    echo $e;
}
// $update = $conn->prepare("UPDATE account
//         SET reset_token_hash = :token_hash,
//             reset_token_expires_at = :expiry
//         WHERE email = :email");

// $update->bindParam("token_hash", $token_hash);
// $update->bindParam("expiry", $expiry);
// $update->bindParam("email", $email);
// $result = $update->execute();

// $stmt = $conn->prepare("SELECT * FROM account WHERE email = ?");
// $stmt->execute([$email]);
// $user = $stmt->fetch();

if ($result) {
    // Store the token, email, and expiration time in the 'password_reset' table
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expiration) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expiry]);

    // Construct the reset link with the token
    $create_link = "https://www.rmuttcpethesis.com/FinalProj/createAccount.php?token=$token";

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
        $mail->Subject = 'Create New Account';
        $mail->Body = "To create your account, <br>
        token : $token <br>
        click on the following link: <a href='$create_link'>to create your account</a>";

        $mail->send();
        echo '<script>alert("send mail complete");</script>';
        echo '<script>window.location.href = "officer_add.php";</script>';

        // echo 'Email sent successfully. Check your inbox for the reset link.';
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
    // $_SESSION['email'] = $email;
    // header("Location: reset_password.php?token=$token"); // ส่งค่า token ไปด้วยใน URL
    // exit();
} else {
    echo '<script>alert("mail cant be send ");</script>';
    echo '<script>window.location.href = "officer_add.php";</script>';
    // echo 'Email not found in our records.';
}


// $mail->setFrom("rmuttcp@rmuttcpethesis.com", "Admin");
// $mail->addAddress($email, $mailName);
// $mail->Subject = $subject;
// $mail->Body = <<<END

//     Click <a href="https://www.rmuttcpethesis.com/FinalProj/createAccount.php?token=$token">here</a> 
//     to create your account.

//     END;

// try {

//     $mail->send();
// } catch (Exception $e) {

//     echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
// }
// echo "Message sent, please check your inbox";
// echo "<script type=\"text/javascript\">
// alert('Message sent, please check your inbox');
// </script>.";
// header("Location: officer_add.php?sucess=sucess");
