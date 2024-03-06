<?php
// Assuming you have already established a database connection, replace with your actual database credentials
require "dbconnect.php";

try {
    // Get JSON data from the POST request
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);

    // Update members table
    foreach ($data['members'] as $member) {
        $accountId = $member['account_id'];
        $value = $member['value'];
        $stmt = $conn->prepare("UPDATE account SET member_manage_permission = :value WHERE account_id = :account_id");
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Update document table
    foreach ($data['document'] as $document) {
        $accountId = $document['account_id'];
        $value = $document['value'];
        $stmt = $conn->prepare("UPDATE account SET thesis_manage_permission	 = :value WHERE account_id = :account_id");
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Update status table
    foreach ($data['status'] as $status) {
        $accountId = $status['account_id'];
        $value = $status['value'];
        $stmt = $conn->prepare("UPDATE account SET status = :value WHERE account_id = :account_id");
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);
        $stmt->bindParam(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->execute();
    }

    echo '1'; // Success response

} catch (PDOException $e) {
    echo '0'; // Error response
    // You might want to log the error for debugging purposes
    // error_log($e->getMessage());
}
?>
