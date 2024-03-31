<?php

require_once("dbconnect.php");
try {
    $stmt = $conn->prepare("SELECT positionName FROM academic_positions");
    $stmt->execute();
    $rows_position = $stmt->fetchAll(); 
} catch (PDOException $e) {
    echo "failed database : " . $e->getMessage();
} finally {
    $conn = null;
    $stmt = null;
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเล่มปริญญานิพนธ์ - เพิ่มข้อมูลอาจารย์</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/js/bootstrap.min.js">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    $tab = 'active';
    $tabshow = 'show active';
    $tabcsv = '';
    $tabcsvshow = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once 'sendMailCreateAccount.php';
    }
    ?>
    <?php require "template/header.php"; ?>
    <br>
    <div class="container">
        <h1 class="h4 text-primary text-center mb-4">เพิ่มข้อมูลอาจารย์</h1>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $tab; ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">กรอกข้อมูล</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $tabcsv; ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">อัปโหลดไฟล์ CSV</button>
            </li>

        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- tab manual -->


            <div class="tab-pane fade <?php echo $tabshow; ?>" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <form class="container mt-4" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="prefix" class="form-label">คำนำหน้า</label>
                            <select class="form-select" id="prefix" name="prefix" onchange="otherShow()" required>
                                <option value="">กรุณาเลือกคำนำหน้า</option>
                                <?php foreach ($rows_position as $position) : ?>
                                    <option value="<?= $position['positionName'] ?>"><?= $position['positionName'] ?></option>
                                <?php endforeach; ?>
                                <option value="other">อื่นๆ</option>
                            </select>
                        </div>
                        <div class="col-md-2" id="other" name="other" style="display: none;" required>
                            <label for="name" class="form-label">คำนำหน้า</label>
                            <input type="text" class="form-control" id="otherPrefix" name="otherPrefix" placeholder="กรุณาใส่คำนำหน้า">
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="กรุณาใส่ชื่อ" required>
                        </div>
                        <div class="col-md-4">
                            <label for="lastname" class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="กรุณาใส่นามสกุล" required>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col">
                            <label for="email" class="form-label">อีเมล์</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="กรุณาใส่อีเมล์" required>
                        </div>
                    </div>
                    <div hidden>
                        <input type="text" id="role" name="role" value="4">
                        <input type="text" id="download_permissions" name="download_permissions" value="1">
                        <input type="text" id="member_manage_permission" name="member_manage_permission" value="0">
                        <input type="text" id="account_manage_permission" name="account_manage_permission" value="0">
                        <input type="text" id="status" name="status" value="1">
                        <input type="text" id="page" name="page" value="teacher_add">

                    </div>
                    <button type="submit" class="btn btn-primary mt-3">ส่งอีเมล์เพื่อสร้างรหัสผ่าน</button>
                </form>
            </div>


            <!-- tab csv -->
            <div class="tab-pane fade <?php echo $tabcsvshow; ?>" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <?php
                if (count($error_msg["name"]) > 0 && count($error_msg["lastname"]) > 0 && count($error_msg["email"]) > 0) {
                    echo '<div class="container mt-5">';
                    echo "<div>รายการที่ไม่สามารถเพิ่มผู้ใช้ได้</div>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<th>คำนำหน้า</th>";
                    echo "<th>ชื่อ</th>";
                    echo "<th>นามสกุล</th>";
                    echo "<th>อีเมล์</th>";
                    echo "</tr>";
                    $count = count($error_msg["name"]);
                    for ($i = 0; $i < $count; $i++) {
                        echo "<tr>";
                        echo "<td>" . $error_msg['prefix'][$i] . "</td>";
                        echo "<td>" . $error_msg['name'][$i] . "</td>";
                        echo "<td>" . $error_msg['lastname'][$i] . "</td>";
                        echo "<td>" . $error_msg['email'][$i] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                    echo '</div>';
                }
                ?>
                <div class="container mt-5">
                    <form method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="form-group">
                            <label for="csvFile" class="mr-2">Choose a CSV file:</label>
                            <input type="file" id="csvFile" name="csvFile" class="form-control-file" accept=".csv">
                            <div id="tableContainer" class="mb-3"></div>
                        </div>
                        <div hidden>
                            <input type="text" id="role" name="role" value="4">
                            <input type="text" id="download_permissions" name="download_permissions" value="1">
                            <input type="text" id="member_manage_permission" name="member_manage_permission" value="0">
                            <input type="text" id="account_manage_permission" name="account_manage_permission" value="0">
                            <input type="text" id="status" name="status" value="1">
                            <input type="text" id="page" name="page" value="teacher_add">

                        </div>
                        <label for="tem">Template สำหรับ CSV</label>
                        <a href="/FinalProj/template/templateTeacher.csv" id="tem" download>
                            <img src="/FinalProj/img/csvlogo.jpg" alt="csv" width="50" height="50">
                        </a>
                        <br>
                        <button type="submit" class="btn btn-primary ml-2">Upload</button>
                    </form>
                </div>
            </div>

        </div>
        <br>
    </div>
    <script>
        function otherShow() {
            var prefixSelect = document.getElementById("prefix");
            var otherDiv = document.getElementById("other");

            if (prefixSelect.value === "other") {
                otherDiv.style.display = "block";
                otherDiv.setAttribute("required", "required");
            } else {
                otherDiv.style.display = "none";
                otherDiv.removeAttribute("required");
            }
        }

        document.getElementById('csvFile').addEventListener('change', handleFile);

        function handleFile(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const csvData = e.target.result;
                    displayTable(csvData);
                };

                reader.readAsText(file);
            }
        }

        function displayTable(csvData) {
            const rows = csvData.split('\n');
            const tableContainer = document.getElementById('tableContainer');

            let tableHTML = '<table>';
            let count = 0;
            let row_count = 0;
            rows.forEach(row => {

                const columns = row.split(',');
                if (columns.some(column => column.trim() !== '')) { // Check if any column is not empty
                    tableHTML += '<tr>';
                    columns.forEach(column => {
                        if (count === 0) {
                            if (column.trim() !== "")
                                tableHTML += `<th>${column}</th>`;
                        } else {
                            if (column.trim() !== "")
                                tableHTML += `<td>${column}</td>`;
                        }
                        row_count++;
                    });
                    tableHTML += '</tr>';
                    count++;
                } 
            });

            tableHTML += '</table>';  
                    
             if(count==1) 
            tableContainer.innerHTML = "No data in CSV";
            else if (row_count/count==4)
            tableContainer.innerHTML = tableHTML;
            else
            tableContainer.innerHTML = "CSV Incorrect";
        }

        
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>