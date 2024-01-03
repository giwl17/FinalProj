<?php

$token = $_POST["token"];
$password = $_POST["password"];
$checkPassword = $_POST["checkPassword"];

$token_hash = hash("sha256", $token);
$password_hash= hash("sha256", $password);


require_once 'dbconnect.php';
if ( strcmp( $password, $checkPassword )) {
    try {
        $sql = "UPDATE account SET password = :password_hash , reset_token_hash =NULL,reset_token_expires_at =NULL
                WHERE reset_token_hash = :token_hash";
        
        $select = $conn->prepare($sql);
        $select->bindParam("password_hash", $password_hash,PDO::PARAM_STR);
        $select->bindParam("token_hash", $token_hash,PDO::PARAM_STR);
        
        
        $select->execute();
        
        // echo"update password";
        echo '<script>alert("update password complete");</script>';
        echo '<script>window.location.href = "login.php";</script>';
       
        } catch (PDOException $e) {
            echo''. $e->getMessage() .'';
            }
}else{
    echo'Your confirm is not correct';
}

?>