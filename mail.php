<?php

require "vendor/autoload.php";
// require_once('class.phpmailer.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mailName = "Admin";
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