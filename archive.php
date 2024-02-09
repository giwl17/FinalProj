<?php
include 'dbconnect.php';

$stmt = $conn->query("SELECT * FROM thesis_document WHERE thesis_status = 2");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archive Data</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php require 'template/header.php'; ?>
    <div class="container mt-5">
        <h1 class="h3 text-center">Archive Data</h1>
        <div class="row">
            <table id="example" class="table table-bordered">
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
                    <?php foreach ($data as $row) : ?>
                        <tr>
                            <td><input type="checkbox" name="select_<?= $row['thesis_id'] ?>" class="select"></td>
                            <td><?= $row['thai_name'] ?></td>
                            <td><?= $row['english_name'] ?></td>
                            <td><?= $row['printed_year'] ?></td>
                            <td><?= $row['semester'] . "/" . $row['approval_year'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-outline-success" onclick="submitPublish()">Publish</button>
            <!-- <div>
                        <button class="btn btn-outline-primary" onclick="submitArchive()">Archive</button>
                    </div>
                    <div>
                        <button class="btn btn-outline-danger" onclick="submitDelete()">Delete</button>
                    </div> -->
        </div>
    </div>
    <div class="container mt-5">
    </div>
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable();
        });

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

        function submitPublish() {
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
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะเผยแพร่',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                })
            } else {
                let checkedList = [];
                selectItems.forEach(item => {
                    console.log(item.name);
                    console.log(item.checked);
                    if (item.checked)
                        checkedList.push(item.name);
                })

                Swal.fire({
                    title: "เผยแพร่รายการที่เลือกหรือไม่?",
                    text: "รายการที่เลือกจะถูกเผยแพร่",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "เผยแพร่รายการที่เลือก"
                }).then((result) => {
                    fetch("/FinalProj/thesis_publish_db.php", {
                        method: "post",
                        body: JSON.stringify(checkedList),
                    }).then(res => {
                        return res.text()
                    }).then(data => {
                        if (data == '1') {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "เผยแพร่สำเร็จ!",
                                    icon: "success"
                                }).then(result => {
                                    window.location.replace("/FinalProj/recycle_bin")
                                })
                            }
                        }
                    })
                });
            }
        }

        function submitArchive() {
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
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการ Archive',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                })
            } else {
                let checkedList = [];
                selectItems.forEach(item => {
                    console.log(item.name);
                    console.log(item.checked);
                    if (item.checked)
                        checkedList.push(item.name);
                })

                Swal.fire({
                    title: "Archive รายการที่เลือกหรือไม่?",
                    text: "รายการที่เลือกจะถูก Archive",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Archive รายการที่เลือก"
                }).then((result) => {
                    fetch("/FinalProj/thesis_archive_db.php", {
                        method: "post",
                        body: JSON.stringify(checkedList),
                    }).then(res => {
                        return res.text()
                    }).then(data => {
                        if (data == '2') {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Archive สำเร็จ!",
                                    icon: "success"
                                }).then(result => {
                                    window.location.replace("/FinalProj/recycle_bin")
                                })
                            }
                        }
                    })
                });
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
                let checkedList = [];
                selectItems.forEach(item => {
                    console.log(item.name);
                    console.log(item.checked);
                    if (item.checked)
                        checkedList.push(item.name);
                })

                Swal.fire({
                    title: "ลบรายการที่เลือกหรือไม่?",
                    text: "รายการที่ลบจะถูกลบอย่างถาวร",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ลบรายการที่เลือก"
                }).then((result) => {
                    fetch("/FinalProj/thesis_delete_db.php", {
                        method: "post",
                        body: JSON.stringify(checkedList),
                    }).then(res => {
                        return res.text()
                    }).then(data => {
                        if (data == '3') {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "ลบสำเร็จ!",
                                    icon: "success"
                                }).then(result => {
                                    window.location.replace("/FinalProj/recycle_bin")
                                })
                            }
                        }
                    })
                });
            }
        }
    </script>

</body>

</html>