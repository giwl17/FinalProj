<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลบหลายรายการ</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
</head>

<body>
    <?php require 'template/header.php'; ?>
    <div class='container d-flex flex-column my-5 gap-3 position-relative'>
        <h1 class="h3 text-center">รายการที่ต้องการลบ</h1>
        <?php
        require "dbconnect.php";
        $stmt = $conn->prepare("SELECT * FROM thesis_document");
        $result = $stmt->execute();
        ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" name="selectAll" id="selectAll" onchange="checkSelectAll(<?= $stmt->rowCount(); ?>)"></th>
                    <th>ชื่อปริญญานิพนธ์ (ภาษาไทย)</th>
                    <th>ชื่อปริญญานิพนธ์ (ภาษาอังกฤษ)</th>
                    <th>ปีที่ตีพิมพ์เล่ม</th>
                    <th>ปีการศึกษา</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stmt as $row) : ?>
                    <tr>
                        <td><input type="checkbox" name="select" class="select"></td>
                        <td><?= $row['thai_name'] ?></td>
                        <td><?= $row['english_name'] ?></td>
                        <td><?= $row['printed_year'] ?></td>
                        <td><?= $row['semester'] . "/" . $row['approval_year'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <button class="btn btn-danger" onclick="submitDelete()">ลบรายการที่เลือก</button>
        </div>
    </div>
    <script>
        function checkSelectAll(count) {
            let selectAll = document.querySelector('#selectAll');
            let selectItems = document.querySelectorAll('.select');
            if (selectAll.checked) {
                selectItems.forEach((item) => {
                    item.checked = true;
                })
            } else {
                selectItems.forEach((item) => {
                    item.checked = false;
                })
            }
        }

        function submitDelete() {
            let anyChecked = false;
            console.log("clicked");
            let selectItems = document.querySelectorAll('.select');
            selectItems.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });
            if (!anyChecked) {
                Swal.fire({
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะลบ',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                })
            } else {
                console.log("any checked");
            }
        }
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/106a60ac58.js" crossorigin="anonymous"></script>


</body>

</html>