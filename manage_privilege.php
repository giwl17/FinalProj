<?php
include 'dbconnect.php';

$stmt = $conn->query("SELECT * FROM account WHERE role = 4");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 1=ผู้ดูแลระบบ
// 2=เจ้าหน้าที่
// 3=เจ้าหน้าที่ชั่วคราว
// 4=อาจารย์
// 5=นักศึกษา
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการสิทธิ์บัญชีผู้ใช้งาน</title>
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
        <h1 class="h3 text-center">จัดการสิทธิ์บัญชีผู้ใช้งาน</h1>
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">นักศึกษา</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">อาจารย์</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">เจ้าหน้าที่</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">เจ้าหน้าที่ชั่วคราว</button>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="row mt-5">
                    <table id="example" class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="selectAll" id="selectAll" onchange="checkSelectAll(<?= $stmt->rowCount(); ?>)"></th>
                                <th>รหัสนักศึกษา</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>สิทธิ์ในการดาว์นโหลดไฟล์</th>
                                <th>สถานะการใช้งาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row) : ?>
                                <tr>
                                    <td><input type="checkbox" name="select_<?= $row['account_id'] ?>" class="select"></td>
                                    <td><?= $row['studentId'] ?></td>
                                    <td><?= $row['prefix'] . $row['name'] . "&nbsp" . $row['lastname'] ?></td>
                                    <td><?= $row['download_permissions'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <div>
                        <button class="btn btn-outline-success" onclick="submitPublish()">Publish</button>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary" onclick="submitArchive()">Archive</button>
                    </div>
                    <div>
                        <button class="btn btn-outline-danger" onclick="submitDelete()">Delete</button>
                    </div>
                </div>
            </div>

            <div class="container mt-5"></div>
            <!-- //tep อาจารย์ -->
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <div class="row mt-5">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="selectAll" id="selectAll" onchange="checkSelectAll(<?= $stmt->rowCount(); ?>)"></th>
                                <th>รหัสนักศึกษา</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>สิทธิ์ในการดาว์นโหลดไฟล์</th>
                                <th>สถานะการใช้งาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row) : ?>
                                <tr>
                                    <td><input type="checkbox" name="select_<?= $row['account_id'] ?>" class="select"></td>
                                    <td><?= $row['studentId'] ?></td>
                                    <td><?= $row['prefix'] . $row['name'] . "&nbsp" . $row['lastname'] ?></td>
                                    <td><?= $row['download_permissions'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <div>
                        <button class="btn btn-outline-success" onclick="submitPublish()">Publish</button>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary" onclick="submitArchive()">Archive</button>
                    </div>
                    <div>
                        <button class="btn btn-outline-danger" onclick="submitDelete()">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable();
        });
        $(document).ready(function() {
            var table = $('#example1').DataTable();
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
    <script>
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
            rows.forEach(row => {

                const columns = row.split(',');
                tableHTML += '<tr>';
                columns.forEach(column => {
                    if (count == 0) {
                        if (column != "")
                            tableHTML += `<th>${column}</th>`;
                    } else {
                        if (column != "")
                            tableHTML += `<td>${column}</td>`;
                    }
                });
                tableHTML += '</tr>';
                count++;
            });

            tableHTML += '</table>';
            tableContainer.innerHTML = tableHTML;
        }
    </script>
</body>

</html>