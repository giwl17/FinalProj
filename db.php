<?php
require "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $ids = $data['thesis_id'];

    // ตั้งค่า query ภายใน loop จะให้ทำการ prepare และ execute query แยกกัน
    $stmt = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_document.thesis_id = :thesis_id");

    // loop ผ่าน array และ execute สำหรับแต่ละ id
    foreach ($ids as $id) {
        // bind parameter ภายใน loop ก่อน execute เพื่อให้พารามิเตอร์เปลี่ยนไปตามค่าของ $id ในแต่ละรอบ
        $stmt->bindParam(":thesis_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // เปลี่ยนจาก fetch เป็น fetchAll เพื่อให้ได้ข้อมูลทั้งหมด

        foreach ($rows as $row) {
            $pdfPathT = $row['thesis_file'];
            $partsT = explode("/", $pdfPathT);
            $newPdfPathT = end($partsT);

            $pdfPathA = $row['approval_file'];
            $partsA = explode("/", $pdfPathA);
            $newPdfPathA = end($partsA);

            $pdfPathP = $row['poster_file'];
            $partsP = explode("/", $pdfPathP);
            $newPdfPathP = end($partsP);

            $pdfT = "FileStorage/thesis/" . $newPdfPathT;
            $pdfA = "FileStorage/approval/" . $newPdfPathA;
            $pdfP = "FileStorage/poster/" . $newPdfPathP;

            // ตรวจสอบว่าไฟล์มีอยู่จริงก่อนลบ
            if (file_exists($pdfT) && unlink($pdfT)) {
                echo "ลบไฟล์ $pdfT สำเร็จ<br>";
            } else {
                echo "เกิดข้อผิดพลาดในการลบไฟล์ $pdfT หรือไฟล์ไม่มีอยู่<br>";
            }

            if (file_exists($pdfA) && unlink($pdfA)) {
                echo "ลบไฟล์ $pdfA สำเร็จ<br>";
            } else {
                echo "เกิดข้อผิดพลาดในการลบไฟล์ $pdfA หรือไฟล์ไม่มีอยู่<br>";
            }

            if (file_exists($pdfP) && unlink($pdfP)) {
                echo "ลบไฟล์ $pdfP สำเร็จ<br>";
            } else {
                echo "เกิดข้อผิดพลาดในการลบไฟล์ $pdfP หรือไฟล์ไม่มีอยู่<br>";
            }
        }
    }
    // echo "3";
}
