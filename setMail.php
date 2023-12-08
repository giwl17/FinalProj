<?php

    $email = addslashes($_POST['email']);
    $subject = "Test";
    $message = "Khathawut";

    // $to = "rmuttcp@rmuttcpethesis.com";
    $to = $email;

    // $header = "From : " . $email . "\r\n";
    $header = "Test Mailer" . "\r\n";
    $header .= "Content-Type: text/html;charset=UTF-8" . "\r\n";
    $header .= "From : <rmuttcp@rmuttcpethesis.com>"  . "\r\n";
    // $header .= "MIME-Version: 1.0\r\n";

    $retval = mail($to, $subject, $message, $header);

