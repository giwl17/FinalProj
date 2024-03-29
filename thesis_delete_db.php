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

    $delete_aut = "DELETE FROM author_thesis WHERE thesis_id IN (";
    $delete_doc = "DELETE FROM thesis_document WHERE thesis_id IN (";

    // สร้าง array เพื่อเก็บ thesis_id ที่จะใช้ในการลบข้อมูล
    $thesis_ids = [];

    foreach ($data as $i) {
        $id = explode("_", $i);
        $id = end($id);

        // เพิ่ม thesis_id เข้าไปใน array
        $thesis_ids[] = $id;

        $stmt = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_id = :thesis_id");
        $stmt->bindParam(":thesis_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $pdfPathT = $row['thesis_file'];
            $partsT = explode("/", $pdfPathT);
            $newPdfPathT = end($partsT);
            $pdfT = "FileStorage/thesis/" . $newPdfPathT;

            if (file_exists($pdfT) && unlink($pdfT)) {
                echo "ลบไฟล์ $pdfT สำเร็จ<br>";
            }

            $pdfPathA = $row['approval_file'];
            $partsA = explode("/", $pdfPathA);
            $newPdfPathA = end($partsA);
            $pdfA = "FileStorage/approval/" . $newPdfPathA;

            if (file_exists($pdfA) && unlink($pdfA)) {
                echo "ลบไฟล์ $pdfA สำเร็จ<br>";
            }

            $pdfPathP = $row['poster_file'];
            $partsP = explode("/", $pdfPathP);
            $newPdfPathP = end($partsP);
            $pdfP = "FileStorage/poster/" . $newPdfPathP;

            if (file_exists($pdfP) && unlink($pdfP)) {
                echo "ลบไฟล์ $pdfP สำเร็จ<br>";
            }
        }
    }

    // สร้างสตริงสำหรับการ bind parameter ของ query ลบข้อมูลในตาราง author_thesis
    $delete_aut .= implode(",", array_fill(0, count($thesis_ids), "?")) . ")";
    $stmt = $conn->prepare($delete_aut);
    $stmt->execute($thesis_ids);

    // สร้างสตริงสำหรับการ bind parameter ของ query ลบข้อมูลในตาราง thesis_document
    $delete_doc .= implode(",", array_fill(0, count($thesis_ids), "?")) . ")";
    $stmt = $conn->prepare($delete_doc);
    $stmt->execute($thesis_ids);

    echo "3";
}
?>
<!-- <?php
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
        ?> -->