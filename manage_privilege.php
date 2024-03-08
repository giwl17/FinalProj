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
    <?php
    require 'template/header.php';
    include 'dbconnect.php';
    if (isset($_SESSION['role'])) {
        // 1=ผู้ดูแลระบบ
        // 2=เจ้าหน้าที่
        // 3=เจ้าหน้าที่ชั่วคราว
        // 4=อาจารย์
        // 5=นักศึกษา
        if ($_SESSION['role'] == 1) {
            $role = 'ผู้ดูแลระบบ';
        } else if ($_SESSION['role'] == 2) {
            $role = 'เจ้าหน้าที่';
        } else if ($_SESSION['role'] == 3) {
            $role = 'เจ้าหน้าที่ชั่วคราว';
        } else if ($_SESSION['role'] == 4) {
            $role = 'อาจารย์';
        } else if ($_SESSION['role'] == 5) {
            $role = 'นักศึกษา';
        }
    ?>
        <div class="container mt-5">
            <h1 class="h3 text-center">จัดการสิทธิ์บัญชีผู้ใช้งาน</h1>
            <div class="row">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">นักศึกษา</button>
                    </li>
                    <?php if ($role == 'ผู้ดูแลระบบ' or $role == 'เจ้าหน้าที่') : ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-teacher" data-bs-toggle="tab" data-bs-target="#profile-tab-teacher" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">อาจารย์</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-officer" data-bs-toggle="tab" data-bs-target="#profile-tab-officer" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">เจ้าหน้าที่</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-temporary" data-bs-toggle="tab" data-bs-target="#profile-tab-temporary" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">เจ้าหน้าที่ชั่วคราว</button>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent">
                <!-- tap student -->
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="row mt-5">
                        <?php
                        $stu = $conn->query("SELECT * FROM account WHERE role = 5");
                        $data = $stu->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <table id="example" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>รหัสนักศึกษา</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>E-mail</th>
                                    <th><input type="checkbox" name="selectAll" id="selectPermissions" onchange="checkPermissionsAll(<?= $stu->rowCount(); ?>)"> สิทธิ์ในการดาว์นโหลดไฟล์</th>
                                    <th><input type="checkbox" name="selectAll" id="selectStatus" onchange="checkStatusAll(<?= $stu->rowCount(); ?>)"> สถานะการใช้งาน</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row) : ?>
                                    <tr>
                                        <td><?= $row['studentId'] ?></td>
                                        <td><?= $row['prefix'] . $row['name'] . "&nbsp" . $row['lastname'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><input type="checkbox" name="permissions_<?= $row['account_id'] ?>" value='1' <?= ($row['download_permissions'] == 1 ? 'checked' : ''); ?> class="permissions"></td>
                                        <td><input type="checkbox" name="status_<?= $row['account_id'] ?>" value='1' <?= ($row['status'] == 1 ? 'checked' : ''); ?> class="status"></td>
                                        <td>
                                            <a class="btn btn-warning" onclick="updateAccount(<?php echo $row['account_id'] ?>);">แก้ไข</a>
                                            <a class="btn btn-danger" onclick="deleteAccount(<?php echo $row['account_id'] ?>);">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-outline-success" onclick="submitStudent()">ยืนยัน</button>
                    </div>
                    <div class="d-flex justify-content-center mt-5"></div>
                </div>
                <!-- tap อาจารย์ -->
                <div class="tab-pane fade" id="profile-tab-teacher" role="tabpanel" aria-labelledby="profile-teacher" tabindex="0">
                    <div class="row mt-5">
                        <?php
                        $teacher = $conn->query("SELECT * FROM account WHERE role = 4");
                        $dataTeacher = $teacher->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>E-mail</th>
                                    <th><input type="checkbox" name="selectAll" id="teacherMembers" onchange="teacherMembersAll(<?= $teacher->rowCount(); ?>)"> จัดการสมาชิกนักศึกษา</th>
                                    <th><input type="checkbox" name="selectAll" id="teacherAccount" onchange="teacherAccountAll(<?= $teacher->rowCount(); ?>)"> จัดการสิทธิ์นักศึกษา</th>
                                    <th><input type="checkbox" name="selectAll" id="teacherDocument" onchange="teacherDocumentAll(<?= $teacher->rowCount(); ?>)"> จัดการเล่มปริญญานิพนธ์</th>
                                    <th><input type="checkbox" name="selectAll" id="teacherStatus" onchange="teacherStatusAll(<?= $teacher->rowCount(); ?>)"> สถานะการใช้งาน</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTeacher as $row) : ?>
                                    <tr>
                                        <td><?= $row['prefix'] . $row['name'] . "&nbsp" . $row['lastname'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><input type="checkbox" name="members_<?= $row['account_id'] ?>" value='1' <?= ($row['member_manage_permission'] == 1 ? 'checked' : ''); ?> class="membersTeacher"></td>
                                        <td><input type="checkbox" name="account_<?= $row['account_id'] ?>" value='1' <?= ($row['account_manage_permission'] == 1 ? 'checked' : ''); ?> class="accountTeacher"></td>
                                        <td><input type="checkbox" name="document_<?= $row['account_id'] ?>" value='1' <?= ($row['thesis_manage_permission'] == 1 ? 'checked' : ''); ?> class="documentTeacher"></td>
                                        <td><input type="checkbox" name="teacher_<?= $row['account_id'] ?>" value='1' <?= ($row['status'] == 1 ? 'checked' : ''); ?> class="statusTeacher"></td>
                                        <td>
                                            <a class="btn btn-warning" onclick="updateAccount(<?php echo $row['account_id'] ?>);">แก้ไข</a>
                                            <a class="btn btn-danger" onclick="deleteAccount(<?php echo $row['account_id'] ?>);">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <button class="btn btn-outline-success" onclick="submitTeacher()">ยืนยัน</button>
                    </div>
                    <div class="d-flex justify-content-center mt-5"></div>
                </div>
                <!-- tap เจ้าหน้าที่ -->
                <div class="tab-pane fade" id="profile-tab-officer" role="tabpanel" aria-labelledby="profile-officer" tabindex="0">
                    <div class="row mt-5">
                        <?php
                        $officer = $conn->query("SELECT * FROM account WHERE role = 2");
                        $dataOfficer = $officer->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <table id="example2" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>E-mail</th>
                                    <th><input type="checkbox" name="selectAll" id="officerMembers" onchange="officerMembersAll(<?= $officer->rowCount(); ?>)"> จัดการสมาชิก</th>
                                    <th><input type="checkbox" name="selectAll" id="officerAccount" onchange="officerAccountAll(<?= $officer->rowCount(); ?>)"> จัดการสิทธิ์</th>
                                    <th><input type="checkbox" name="selectAll" id="officerDocument" onchange="officerDocumentAll(<?= $officer->rowCount(); ?>)"> จัดการเล่มปริญญานิพนธ์</th>
                                    <th><input type="checkbox" name="selectAll" id="officerStatus" onchange="officerStatusAll(<?= $officer->rowCount(); ?>)"> สถานะการใช้งาน</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataOfficer as $row) : ?>
                                    <tr>
                                        <td><?= $row['prefix'] . $row['name'] . "&nbsp" . $row['lastname'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><input type="checkbox" name="members_<?= $row['account_id'] ?>" value='1' <?= ($row['member_manage_permission'] == 1 ? 'checked' : ''); ?> class="MembersOfficer"></td>
                                        <td><input type="checkbox" name="account_<?= $row['account_id'] ?>" value='1' <?= ($row['account_manage_permission'] == 1 ? 'checked' : ''); ?> class="AccountOfficer"></td>
                                        <td><input type="checkbox" name="document_<?= $row['account_id'] ?>" value='1' <?= ($row['thesis_manage_permission'] == 1 ? 'checked' : ''); ?> class="DocumentOfficer"></td>
                                        <td><input type="checkbox" name="status_<?= $row['account_id'] ?>" value='1' <?= ($row['status'] == 1 ? 'checked' : ''); ?> class="StatusOfficer"></td>
                                        <td>
                                            <a class="btn btn-warning" onclick="updateAccount(<?php echo $row['account_id'] ?>);">แก้ไข</a>
                                            <a class="btn btn-danger" onclick="deleteAccount(<?php echo $row['account_id'] ?>);">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <button class="btn btn-outline-success" onclick="submitOfficer()">ยืนยัน</button>
                    </div>
                    <div class="d-flex justify-content-center mt-5"></div>
                </div>
                <!-- tap เจ้าหน้าที่ชั่วคราว -->
                <div class="tab-pane fade" id="profile-tab-temporary" role="tabpanel" aria-labelledby="profile-temporary" tabindex="0">
                    <div class="row mt-5">
                        <?php
                        $temporary = $conn->query("SELECT * FROM account WHERE role = 3");
                        $dataTemporary = $temporary->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <table id="example3" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>E-mail</th>
                                    <th><input type="checkbox" name="selectAll" id="temporaryStatus" onchange="temporaryStatusAll(<?= $temporary->rowCount(); ?>)"> สถานะการใช้งาน</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTemporary as $row) : ?>
                                    <tr>
                                        <td><?= $row['prefix'] . $row['name'] . "&nbsp" . $row['lastname'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><input type="checkbox" name="temporary_<?= $row['account_id'] ?>" value='1' <?= ($row['status'] == 1 ? 'checked' : ''); ?> class="statusTemporary"></td>
                                        <td>
                                            <a class="btn btn-warning" onclick="updateAccount(<?php echo $row['account_id'] ?>);">แก้ไข</a>
                                            <a class="btn btn-danger" onclick="deleteAccount(<?php echo $row['account_id'] ?>);">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        <button class="btn btn-outline-success" onclick="submitTemporary()">ยืนยัน</button>
                    </div>
                    <div class="d-flex justify-content-center mt-5"></div>
                </div>
            </div>
        </div>
    <?php
    } else {
        // header("Location: /FinalProj/");
        // exit();
    }
    // }
    ?>
    <script>
        function updateAccount(id) {
            data = {
                id: id
            }
            fetch('account.php', {
                method: "POST",
                body: JSON.stringify(data)
            }).then(res => {
                return res.json()
            }).then(data => {
                console.log(data)
                let htmlTxt = ''
                if (data.studentId !== null) {
                    htmlTxt =
                        `<div class='d-flex flex-column gap-3'>
                        <div class='form-group text-start'>
                        <label for='student_id' class=''>รหัสนักศึกษา</label>
                        <input type='text' id='student_id' class='form-control' value='${data.studentId}'>
                        </div>
                        <div class='form-group text-start d-flex flex-column gap-2'>
                        <label for='firstname' class=''>ชื่อ-นามสกุล</label>
                        <input type='text' id='prefix' class='form-control' value='${data.prefix}'>
                        <input type='text' id='firstname' class='form-control' value='${data.name}'>
                        <input type='text' id='lastname' class='form-control' value='${data.lastname}'>
                        </div>
                        <div class='form-group text-start'>
                        <label for='email' class=''>Email</label>
                        <input type='email' id='email' class='form-control'  value='${data.email}'>
                        </div>
                    </div>`
                } else {
                    htmlTxt =
                        `<div class='d-flex flex-column gap-3'>
                        <div class='form-group text-start d-flex flex-column gap-2'>
                            <label for='firstname' class=''>ชื่อ-นามสกุล</label>
                            <input type='text' id='prefix' class='form-control' value='${data.prefix}'>
                            <input type='text' id='firstname' class='form-control' value='${data.name}'>
                            <input type='text' id='lastname' class='form-control' value='${data.lastname}'>
                        </div>
                        <div class='form-group text-start'>
                            <label for='email' class=''>Email</label>
                            <input type='email' id='email' class='form-control'  value='${data.email}'>
                        </div>
                        <input type="hidden" id="student_id" value="${data.studentId}">
                    </div>`
                }

                Swal.fire({
                    title: "แก้ไขข้อมูล",
                    html: htmlTxt,
                    showCancelButton: true,
                    confirmButtonText: "Update",
                    preConfirm: () => {
                        data = {
                            "account_id": data.account_id,
                            "student_id": document.getElementById("student_id").value,
                            "prefix": document.getElementById("prefix").value,
                            "firstname": document.getElementById("firstname").value,
                            "lastname": document.getElementById("lastname").value,
                            "email": document.getElementById("email").value,
                        }

                        fetch("account_updata.php", {
                            method: "POST",
                            body: JSON.stringify(data)
                        }).then(res => {
                            return res.text()
                        }).then(data => {
                            if (data == '1') {
                                Swal.fire({
                                    icon: "success",
                                    title: "แก้ไขข้อมูลสำเร็จแล้ว",
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload()
                                    }
                                })
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "มีบางอย่างผิดพลาด"
                                })
                            }
                        })
                    }

                })
            })
        }

        function deleteAccount(id) {
            data = {
                account_id: id
            }
            Swal.fire({
                title: "แน่ใจหรือไม่?",
                text: "ต้องการลบบัญชีผู้ใช้นี้ใช่ไหม",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("account_delete.php", {
                        method: "POST",
                        body: JSON.stringify(data)
                    }).then(res => {
                        return res.text()
                    }).then(data => {
                        if (data == '1') {
                            Swal.fire({
                                title: "ลบสำเร็จ!",
                                text: "คุณได้ลบรายการปริญญานิพนธ์เรียบร้อยแล้ว",
                                icon: "success"
                            }).then(result => {
                                if (result.isConfirmed) {
                                    window.location.reload()
                                }
                            })
                        } else {
                            Swal.fire({
                                title: "มีบางอย่างผิดพลาด",
                                icon: "error"
                            })
                        }
                    })

                }
            })
        }

        $(document).ready(function() {
            // var table = $('#example').DataTable();
            $('#example').DataTable({
                "aLengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "iDisplayLength": 25
            });
            $('#example1').DataTable({
                "aLengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "iDisplayLength": 25
            });
            $('#example2').DataTable({
                "aLengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "iDisplayLength": 25
            });
            $('#example3').DataTable({
                "aLengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "iDisplayLength": 25
            });
        });
        /*  $(document).ready(function() {
             var table = $('#example1').DataTable();
         }); */
    </script>
    <!-- tap student -->
    <script>
        function checkPermissionsAll(count) {
            let selectPermissions = document.querySelector('#selectPermissions');
            let permissions = document.querySelectorAll('.permissions');

            if (selectPermissions.checked) {
                permissions.forEach((item) => {
                    item.checked = true;
                });
            } else {
                permissions.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function checkStatusAll(count) {
            let selectStatus = document.querySelector('#selectStatus');
            let status = document.querySelectorAll('.status');

            if (selectStatus.checked) {
                status.forEach((item) => {
                    item.checked = true;
                });
            } else {
                status.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function submitStudent() {
            let anyChecked = false;
            // console.log("clicked");
            let permissions = document.querySelectorAll('.permissions');
            let status = document.querySelectorAll('.status');

            permissions.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            status.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                Swal.fire({
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะปรับเปลี่ยน',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                });
            } else {
                let checkedListPermissions = [];
                let checkedListStatus = [];

                permissions.forEach(item => {
                    checkedListPermissions.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListPermissions);

                status.forEach(item => {
                    checkedListStatus.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListStatus);


                Swal.fire({
                    title: "ปรับเปลี่ยนรายการที่เลือกหรือไม่?",
                    text: "รายการที่เลือกจะปรับเปลี่ยน",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ปรับเปลี่ยนรายการที่เลือก"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/FinalProj/permissions_student_db.php", {
                                method: "post",
                                body: JSON.stringify({
                                    permissions: checkedListPermissions,
                                    status: checkedListStatus
                                }),
                            }).then(res => res.text())
                            .then(data => {
                                if (data == '1') {
                                    Swal.fire({
                                        title: "ปรับเปลี่ยนสำเร็จ!",
                                        icon: "success"
                                    }).then(() => {
                                        window.location.replace("/FinalProj/manage_privilege");
                                    });
                                }
                            }).catch((error) => {
                                console.error('There was a problem with the fetch operation:', error);
                                Swal.fire({
                                    text: 'มีปัญหาในการส่งข้อมูล',
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            });
                    }
                });
            }
        }
    </script>
    <!-- tap อาจารย์ -->
    <script>
        function teacherMembersAll(count) {
            let teacherMember = document.querySelector('#teacherMembers');
            let membersTeacher = document.querySelectorAll('.membersTeacher');

            if (teacherMember.checked) {
                membersTeacher.forEach((item) => {
                    item.checked = true;
                });
            } else {
                membersTeacher.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function teacherAccountAll(count) {
            let teacherAccount = document.querySelector('#teacherAccount');
            let accountTeacher = document.querySelectorAll('.accountTeacher');

            if (teacherAccount.checked) {
                accountTeacher.forEach((item) => {
                    item.checked = true;
                });
            } else {
                accountTeacher.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function teacherDocumentAll(count) {
            let teacherDoc = document.querySelector('#teacherDocument');
            let docTeacher = document.querySelectorAll('.documentTeacher');

            if (teacherDoc.checked) {
                docTeacher.forEach((item) => {
                    item.checked = true;
                });
            } else {
                docTeacher.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function teacherStatusAll(count) {
            let teacherSta = document.querySelector('#teacherStatus');
            let status = document.querySelectorAll('.statusTeacher');

            if (teacherSta.checked) {
                status.forEach((item) => {
                    item.checked = true;
                });
            } else {
                status.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function submitTeacher() {
            let anyChecked = false;
            // console.log("clicked");
            let membersTeacher = document.querySelectorAll('.membersTeacher');
            let accountTeacher = document.querySelectorAll('.accountTeacher');
            let documentTeacher = document.querySelectorAll('.documentTeacher');
            let statusTeacher = document.querySelectorAll('.statusTeacher');

            membersTeacher.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            documentTeacher.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            statusTeacher.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                Swal.fire({
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะปรับเปลี่ยน',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                });
            } else {
                let checkedListMembersTeacher = [];
                let checkedListAccountTeacher = [];
                let checkedListDocumentTeacher = [];
                let checkedListStatusTeacher = [];

                membersTeacher.forEach(item => {
                    checkedListMembersTeacher.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListMembersTeacher);

                accountTeacher.forEach(item => {
                    checkedListAccountTeacher.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListAccountTeacher);

                documentTeacher.forEach(item => {
                    checkedListDocumentTeacher.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListDocumentTeacher);

                statusTeacher.forEach(item => {
                    checkedListStatusTeacher.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListStatusTeacher);


                Swal.fire({
                    title: "ปรับเปลี่ยนรายการที่เลือกหรือไม่?",
                    text: "รายการที่เลือกจะถูกปรับเปลี่ยน",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ปรับเปลี่ยนรายการที่เลือก"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/FinalProj/permissions_teacher_db.php", {
                                method: "post",
                                body: JSON.stringify({
                                    members: checkedListMembersTeacher,
                                    account: checkedListAccountTeacher,
                                    document: checkedListDocumentTeacher,
                                    status: checkedListStatusTeacher
                                }),
                            }).then(res => res.text())
                            .then(data => {
                                if (data == '1') {
                                    Swal.fire({
                                        title: "ปรับเปลี่ยนสำเร็จ!",
                                        icon: "success"
                                    }).then(() => {
                                        window.location.replace("/FinalProj/manage_privilege");
                                    });
                                }
                            }).catch((error) => {
                                console.error('There was a problem with the fetch operation:', error);
                                Swal.fire({
                                    text: 'มีปัญหาในการส่งข้อมูล',
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            });
                    }
                });
            }
        }
    </script>
    <!-- tap เจ้าหน้าที่ -->
    <script>
        function officerMembersAll(count) {
            let officerMember = document.querySelector('#officerMembers');
            let membersOfficer = document.querySelectorAll('.MembersOfficer');

            if (officerMember.checked) {
                membersOfficer.forEach((item) => {
                    item.checked = true;
                });
            } else {
                membersOfficer.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function officerAccountAll(count) {
            let officerAccount = document.querySelector('#officerAccount');
            let accountOfficer = document.querySelectorAll('.accountOfficer');

            if (officerAccount.checked) {
                accountOfficer.forEach((item) => {
                    item.checked = true;
                });
            } else {
                accountOfficer.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function officerDocumentAll(count) {
            let Doc = document.querySelector('#officerDocument');
            let officerDoc = document.querySelectorAll('.DocumentOfficer');

            if (Doc.checked) {
                officerDoc.forEach((item) => {
                    item.checked = true;
                });
            } else {
                officerDoc.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function officerStatusAll(count) {
            let teacherSta = document.querySelector('#officerStatus');
            let status = document.querySelectorAll('.StatusOfficer');

            if (teacherSta.checked) {
                status.forEach((item) => {
                    item.checked = true;
                });
            } else {
                status.forEach((item) => {
                    item.checked = false;
                });
            }
        }

        function submitOfficer() {
            let anyChecked = false;
            // console.log("clicked");
            let members = document.querySelectorAll('.MembersOfficer');
            let account = document.querySelectorAll('.AccountOfficer');
            let documentOff = document.querySelectorAll('.DocumentOfficer');
            let status = document.querySelectorAll('.StatusOfficer');

            members.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            account.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            documentOff.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            status.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                Swal.fire({
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะปรับเปลี่ยน',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                });
            } else {
                let checkedListMembers = [];
                let checkedListAccount = [];
                let checkedListDocument = [];
                let checkedListStatus = [];

                members.forEach(item => {
                    checkedListMembers.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListMembers);

                account.forEach(item => {
                    checkedListAccount.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListAccount);

                documentOff.forEach(item => {
                    checkedListDocument.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListDocument);

                status.forEach(item => {
                    checkedListStatus.push({
                        account_id: item.name.split('_')[1], // Extract account_id from name attribute
                        value: item.checked ? 1 : 0
                    });
                });
                console.log(checkedListStatus);


                Swal.fire({
                    title: "ปรับเปลี่ยนรายการที่เลือกหรือไม่?",
                    text: "รายการที่เลือกจะถูกปรับเปลี่ยน",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ปรับเปลี่ยนรายการที่เลือก"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/FinalProj/permissions_officer_db.php", {
                                method: "post",
                                body: JSON.stringify({
                                    members: checkedListMembers,
                                    account: checkedListAccount,
                                    document: checkedListDocument,
                                    status: checkedListStatus
                                }),
                            }).then(res => res.text())
                            .then(data => {
                                if (data == '1') {
                                    Swal.fire({
                                        title: "ปรับเปลี่ยนสำเร็จ!",
                                        icon: "success"
                                    }).then(() => {
                                        window.location.replace("/FinalProj/manage_privilege");
                                    });
                                }
                            }).catch((error) => {
                                console.error('There was a problem with the fetch operation:', error);
                                Swal.fire({
                                    text: 'มีปัญหาในการส่งข้อมูล',
                                    icon: 'error',
                                    confirmButtonText: 'ตกลง'
                                });
                            });
                    }
                });
            }
        }
    </script>
    <!-- tap เจ้าหน้าที่ชั่วคราว -->
    <script>
        function temporaryStatusAll(count) {
            let temporaryCheckbox = document.querySelector('#temporaryStatus');
            let temporaryCheckboxes = document.querySelectorAll('.statusTemporary');

            if (temporaryCheckbox.checked) {
                temporaryCheckboxes.forEach((checkbox) => {
                    checkbox.checked = true;
                });
            } else {
                temporaryCheckboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
            }
        }

        function submitTemporary() {
            let anyChecked = false;
            let temporaryCheckboxes = document.querySelectorAll('.statusTemporary');

            temporaryCheckboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                Swal.fire({
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะปรับเปลี่ยน',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                });
            } else {
                let checkedListTemporary = [];
                temporaryCheckboxes.forEach((checkbox) => {
                    checkedListTemporary.push({
                        account_id: checkbox.name.split('_')[1], // Extract account_id from name attribute
                        value: checkbox.checked ? 1 : 0
                    });
                });
                console.log(checkedListTemporary);
                Swal.fire({
                    title: "ปรับเปลี่ยนรายการที่เลือกหรือไม่?",
                    text: "รายการที่เลือกจะถูกปรับเปลี่ยน",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ปรับเปลี่ยนรายการที่เลือก"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/FinalProj/permissions_temporary_db.php", {
                            method: "post",
                            body: JSON.stringify({
                                temporary: checkedListTemporary
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        }).then((res) => {
                            if (!res.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return res.text();
                        }).then((data) => {
                            if (data === '1') {
                                Swal.fire({
                                    title: "ปรับเปลี่ยนสำเร็จ!",
                                    icon: "success"
                                }).then(() => {
                                    window.location.replace("/FinalProj/manage_privilege");
                                });
                            }
                        }).catch((error) => {
                            console.error('There was a problem with the fetch operation:', error);
                            Swal.fire({
                                text: 'มีปัญหาในการส่งข้อมูล',
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        });
                    }
                });
            }
        }
    </script>

</body>