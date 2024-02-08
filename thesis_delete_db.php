<?php
require "dbconnect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE thesis_document, author_thesis FROM thesis_document
                                LEFT JOIN author_thesis ON thesis_document.thesis_id = author_thesis.thesis_id
                                WHERE thesis_document.thesis_id = :thesis_id");
        $stmt->bindParam(":thesis_id", $id);
        $stmt->execute();
        header('location: /FinalProj');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = [];
    foreach ($data as $i) {
        $data_ex  = explode("_", $i);
        $data_ex = array_pop($data_ex);
        array_push($id, $data_ex);
    }
    $sql = "DELETE thesis_document, author_thesis FROM thesis_document
            LEFT JOIN author_thesis ON thesis_document.thesis_id = author_thesis.thesis_id
            WHERE thesis_document.thesis_id = $id[0]";
    for ($i = 1; $i < count($id); $i++) {
        $sql .= " OR thesis_document.thesis_id = $id[$i]";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo "3"; // Change the echoed response value to "3"
}
