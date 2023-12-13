<?php

/* $email = addslashes($_POST['email']);
    $subject = "Test";
    $message = "Khathawut";

    // $to = "rmuttcp@rmuttcpethesis.com";
    $to = $email;

    $header = "Test Mailer" . "\r\n";
    $header .= "Content-Type: text/html;charset=UTF-8" . "\r\n";
    $header .= "From : <rmuttcp@rmuttcpethesis.com>"  . "\r\n";

    $retval = mail($to, $subject, $message, $header); */

require "vendor/autoload.php";
// require_once('class.phpmailer.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$name = "Admin";
$email = $_POST["email"];
$subject = $_POST["name"];
$message = "Hello test mailer!";

$mail->IsHTML(true);
$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "rmuttcpethesis.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 25;

$mail->Username = "rmuttcp";
$mail->Password = "xG5qK2sg43";

$mail->setFrom("rmuttcp@rmuttcpethesis.com", "Admin");
$mail->addAddress($email, $name);

$mail->Subject = $subject;
$mail->Body = $message;

$mail->send();

if ($mail->send()) {
    echo "success!";
  } else {
    echo "fail...";
  }

header("Location: login.php");