<?php
require "dbconnect.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("UPDATE thesis_document SET thesis_status = 2 WHERE thesis_id = :thesis_id");
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
    $sql = "UPDATE thesis_document SET thesis_status = 2 WHERE thesis_id = $id[0]";
    for ($i = 1; $i < count($id); $i++) {
        $sql .= " OR thesis_id = $id[$i]";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo "2"; // Change the echoed response value to "2"
}
?>
