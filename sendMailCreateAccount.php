<?php
$prefix = $_POST["prefix"];
$name = $_POST["name"];
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$role =  $_POST["role"];
$download_permissions = $_POST["download_permissions"];
$member_manage_permission = $_POST["member_manage_permission"];
$account_manage_permission = $_POST["account_manage_permission"];



$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);
require_once 'dbconnect.php';
require "mail.php";
// $sql = $conn->prepare
$insert = $conn->prepare("INSERT INTO account (password,prefix,name,lastname,email,role,download_permissions,member_manage_permission,account_manage_permission)
        VALUES(:token,:prefix,:name,:lastname,:email,:role,:download_permissions,:member_manage_permission,:account_manage_permission)");
$insert->bindParam("token", $token);
$insert->bindParam("prefix", $prefix);
$insert->bindParam("name", $name);
$insert->bindParam("lastname", $lastname);
$insert->bindParam("email", $email);
$insert->bindParam("role", $role);
$insert->bindParam("download_permissions", $download_permissions);
$insert->bindParam("member_manage_permission", $member_manage_permission);
$insert->bindParam("account_manage_permission", $account_manage_permission);
$result = $insert->execute();

$update = $conn->prepare("UPDATE account
        SET reset_token_hash = :token_hash,
            reset_token_expires_at = :expiry
        WHERE email = :email");

$update->bindParam("token_hash", $token_hash);
$update->bindParam("expiry", $expiry);
$update->bindParam("email", $email);
$result = $update->execute();
// $stmt = $mysqli->prepare($sql);

// $stmt->bind_param("sss", $token_hash, $expiry, $email);

// $stmt->execute(); 


$mail->setFrom("rmuttcp@rmuttcpethesis.com", "Admin");
$mail->addAddress($email, $mailName);
$mail->Subject = $subject;
$mail->Body = <<<END

    Click <a href="https://www.rmuttcpethesis.com/FinalProj/createAccount.php?token=$token">here</a> 
    to create your account.

    END;

try {

    $mail->send();
} catch (Exception $e) {

    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
}

echo "<script type=\"text/javascript\">
alert('Message sent, please check your inbox');
</script>.";
header("Location: officer_add.php");

