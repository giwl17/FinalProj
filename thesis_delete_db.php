<?php
require "dbconnect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        // Delete from author_thesis table
        $stm = $conn->prepare("DELETE FROM author_thesis WHERE author_thesis.thesis_id = :thesis_id");
        $stm->bindParam(":thesis_id", $id);
        $stm->execute();

        // Delete from thesis_document table
        $stmt = $conn->prepare("DELETE FROM thesis_document WHERE thesis_document.thesis_id = :thesis_id");
        $stmt->bindParam(":thesis_id", $id);
        $stmt->execute();

        // Redirect after successful deletion
        header('location: /FinalProj');
    } catch (PDOException $e) {
        // Log the error in a production environment
        error_log("Error: " . $e->getMessage(), 0);

        // Display a generic error message to the user
        echo "An error occurred. Please try again later.";
    }
} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = [];
    foreach ($data as $i) {
        $data_ex  = explode("_", $i);
        $data_ex = array_pop($data_ex);
        array_push($id, $data_ex);
    }
    $delete_aut = "DELETE FROM author_thesis WHERE author_thesis.thesis_id = $id[0]";
    for ($i = 1; $i < count($id); $i++) {
        $delete_aut .= " OR author_thesis.thesis_id = $id[$i]";
    }

    $delete_doc = "DELETE FROM thesis_document WHERE thesis_document.thesis_id = $id[0]";
    for ($i = 1; $i < count($id); $i++) {
        $delete_doc .= " OR thesis_document.thesis_id = $id[$i]";
    }
    $stmt = $conn->prepare($delete_aut);
    $stmt->execute();
    $stmt = $conn->prepare($delete_doc);
    $stmt->execute();
    echo "3"; // Change the echoed response value to "3"
}
