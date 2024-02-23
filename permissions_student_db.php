<?php
// Define your database credentials
require "dbconnect.php";

try {
    // Decode the JSON data from the request body
    $postData = json_decode(file_get_contents("php://input"), true);

    // Extract permissions and status arrays from the decoded data
    $permissions = $postData['permissions'];
    $status = $postData['status'];

    // Update permissions in the database
    $conn->beginTransaction();
    foreach ($permissions as $permission) {
        $accountId = $permission['account_id'];
        $value = $permission['value'];

        $stmt = $conn->prepare("UPDATE account SET download_permissions = :value WHERE account_id = :account_id");
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':account_id', $accountId);
        $stmt->execute();
    }
    $conn->commit();

    // Update status in the database
    $conn->beginTransaction();
    foreach ($status as $stat) {
        $accountId = $stat['account_id'];
        $value = $stat['value'];

        $stmt = $conn->prepare("UPDATE account SET status = :value WHERE account_id = :account_id");
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':account_id', $accountId);
        $stmt->execute();
    }
    $conn->commit();

    // Send a success response to the client
    echo '1';
} catch (PDOException $e) {
    // Handle errors
    $conn->rollBack();
    echo 'Error: ' . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
