<?php
require_once 'dbconnect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = $_SESSION['email'];  // Change this according to your authentication system

        $newPassword = $_POST['new_password'];

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE account SET password = :password WHERE email = :email");

        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        $response = array('status' => 'success', 'message' => 'เปลี่ยนรหัสผ่านสำเร็จ');
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = array('status' => 'error', 'message' => 'Database error: ' . $e->getMessage());
        echo json_encode($response);
    }
} else {
    // Return error status for invalid request method
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
}
?>
