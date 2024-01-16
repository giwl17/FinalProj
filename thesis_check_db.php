<?php
require "dbconnect.php";

$select = $conn->prepare("SELECT thesis_id, thai_name, english_name, dateTime_import FROM thesis_document WHERE approval_status = 0");
$select->execute();
$result = $select->fetchAll();
// print_r($result);
if ($select->rowCount() > 0) {
    echo "<table class='table'>";
    echo
    "
    <thead>
        <tr>
            <th scope='col'>รายการที่</th>
            <th scope='col'>ชื่อภาษาไทย</th>
            <th scope='col'>ชื่อภาษาอังกฤษ</th>
            <th scope='col'>วันที่เพิ่มข้อมูล</th>
            <th scope='col'>ตรวจสอบ</th>
        </tr>
    </thead>
    ";
    $i = 1;
    echo "<tbody>";
        foreach ($result as $row) {
            echo 
            "       
                <tr>
                    <th scope='row'>$i</th>
                    <td scope='row'>$row[thai_name]</td>
                    <td scope='row'>$row[english_name]</td>
                    <td scope='row'>$row[dateTime_import]</td>
                    <td scope='row'><a class='btn btn-warning' href='thesislistwaiting/thesis?id=$row[thesis_id]'>ตรวจสอบ</a></td>
                </tr>        
            ";
            $i++;
        }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>ไม่มีรายการที่รอตรวจสอบ</p>";
}
