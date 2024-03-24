<?php

require_once("dbconnect.php");
try {
    $stmt = $conn->prepare("SELECT * FROM prefix");
    $stmt->execute();
    $rows_prefix = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "มีบางอย่างผิดพลาดที่ฐานข้อมูล";
} finally {
    $stmt = null;
    $conn = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มเล่มปริญญานิพนธ์</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/js/bootstrap.min.js">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php
    ob_start();
    if (isset($_SESSION['insertDataSuccess'])) {
        echo "<script type='text/javascript'>
        Swal.fire({
            title: 'เพิ่มข้อมูลสำเร็จ',
            icon: 'success',
            showConfirmButton: false,
            timer: 1800
          });
        </script>";
        unset($_SESSION['insertDataSuccess']);
    }
    if (isset($_SESSION['InsertCsvSuccess'])) {
        if ($_SESSION['InsertCsvSuccess']) {
            echo "<script type='text/javascript'>
            Swal.fire({
                title: 'เพิ่มข้อมูล CSV สำเร็จ',
                icon: 'success',
                showConfirmButton: false,
                timer: 1800
              });
            </script>";
        }
        unset($_SESSION['insertDataSuccess']);
    }
    ?>
    <?php require "template/header.php"; ?>
    <div class="container">
        <h1 class="h3 text-primary text-center my-4">เพิ่มข้อมูลปริญญานิพนธ์</h1>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">กรอกข้อมูล</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">อัปโหลดไฟล์ CSV</button>
            </li>
        </ul>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <form class="mt-4" method="post" action="thesis_add_db.php" enctype="multipart/form-data" id="myform1" novalidate>
                    <div class="form-group mb-3">
                        <label for="thesis_name_th">ชื่อปริญญานิพนธ์ (ภาษาไทย)</label>
                        <textarea class="form-control required" name="thesis_name_th" cols="30" rows="4" style="resize: none;" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="thesis_name_en">ชื่อปริญญานิพนธ์ (ภาษาอังกฤษ)</label>
                        <textarea class="form-control" name="thesis_name_en" id="textInput" cols="30" rows="4" style="resize: none;" required></textarea>
                        <div class="invalid-feedback invalid">
                            กรุณากรอกข้อมูลเป็นภาษาอังกฤษ
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="abstract">บทคัดย่อ</label>
                        <textarea class="form-control" name="abstract" cols="30" rows="10" style="resize: none;" required></textarea>
                    </div>

                    <div>
                        <p>คณะผู้จัดทำ</p>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="member1" checked>
                            <label class="form-check-label" for="member1">สมาชิกคนที่ 1</label>
                            <div>
                                <div class="form-group mb-3">
                                    <label for="member1_id">รหัสนักศึกษา (มีขีด)</label>
                                    <input class="form-control members_id" type="text" name="member1_id" id="member1_id" maxlength="14" pattern="^[0-9]{12}-[0-9]$" placeholder="xxxxxxxxxxxx-x" required>
                                </div>
                                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                                    <div class="col form-group">
                                        <label for="member1_prefix">คำนำหน้า</label>
                                        <select class="form-select" name="member1_prefix" required>
                                            <option value=""></option>
                                            <?php foreach ($rows_prefix as $row) : ?>
                                                <option value="<?= $row['prefixName'] ?>"><?= $row['prefixName'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col form-group">
                                        <label for="">ชื่อ</label>
                                        <input class="form-control" type="text" name="member1_firstname" required>
                                    </div>
                                    <div class="col form-group">
                                        <label for="">นามสกุล</label>
                                        <input class="form-control" type="text" name="member1_lastname" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check member">
                            <input class="form-check-input" type="checkbox" name="member2">
                            <label class="form-check-label" for="member2">สมาชิกคนที่ 2</label>
                            <div class="form-group mb-3 member-child">
                                <label for="member2_id">รหัสนักศึกษา (มีขีด)</label>
                                <input class="form-control members_id" type="text" name="member2_id" id="member2_id" maxlength="14" pattern="^[0-9]{12}-[0-9]$" placeholder="xxxxxxxxxxxx-x">
                            </div>
                            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                                <div class="col form-group">
                                    <label for="member2_prefix">คำนำหน้า</label>
                                    <select class="form-select" name="member2_prefix" id="member2_prefix">
                                        <option value=""></option>
                                        <?php foreach ($rows_prefix as $row) : ?>
                                            <option value="<?= $row['prefixName'] ?>"><?= $row['prefixName'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="member2_firstname">ชื่อ</label>
                                    <input class="form-control" type="text" name="member2_firstname" id="member2_firstname">
                                </div>
                                <div class="col form-group">
                                    <label for="member2_lastname">นามสกุล</label>
                                    <input class="form-control" type="text" name="member2_lastname" id="member2_lastname">
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="member3" onchange="checkMemberReq(this)">
                            <label class="form-check-label" for="member3">สมาชิกคนที่ 3</label>
                            <div>
                                <div class="form-group mb-3">
                                    <label for="member3_id">รหัสนักศึกษา (มีขีด)</label>
                                    <input class="form-control members_id" type="text" name="member3_id" id="member3_id" maxlength="14" pattern="^[0-9]{12}-[0-9]$" placeholder="xxxxxxxxxxxx-x">
                                </div>
                                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                                    <div class="col form-group">
                                        <label for="member3_prefix">คำนำหน้า</label>
                                        <select class="form-select" name="member3_prefix" id="member3_prefix">
                                            <option value=""></option>
                                            <?php foreach ($rows_prefix as $row) : ?>
                                                <option value="<?= $row['prefixName'] ?>"><?= $row['prefixName'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col form-group">
                                        <label for="member3_firstname">ชื่อ</label>
                                        <input class="form-control" type="text" name="member3_firstname" id="member3_firstname">
                                    </div>
                                    <div class="col form-group">
                                        <label for="member3_lastname">นามสกุล</label>
                                        <input class="form-control" type="text" name="member3_lastname" id="member3_lastname">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="formAdvisor">
                        <label for="advisor">อาจารย์ที่ปรึกษาหลัก</label>
                        <select class="form-select" name="advisor" id="advisor" onchange="advisorChange()" required>
                            <option value=""></option>
                            <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา">ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ">ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี">ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                            <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์">ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                            <option value="ดร. ปอลิน กองสุวรรณ">ดร.ปอลิน กองสุวรรณ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล">ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                            <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์">อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์">ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                            <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา">รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม">ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                            <option value="ดร. พิชยพัชยา ศรีคร้าม">ดร.พิชยพัชยา ศรีคร้าม</option>
                            <option value="other">อื่น ๆ</option>
                        </select>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3" id="advisor_other" hidden>
                        <div class="col form-group">
                            <label for="advisor_other_prefix">คำนำหน้า</label>
                            <select class="form-select" name="advisor_other_prefix">
                                <option value=""></option>
                                <option value="ดร.">ดร.</option>
                                <option value="ศ.">ศ.</option>
                                <option value="ศ.ดร.">ศ.ดร.</option>
                                <option value="รศ.">รศ.</option>
                                <option value="รศ.ดร.">รศ.ดร.</option>
                                <option value="ผศ.">ผศ.</option>
                                <option value="ผศ.ดร.">ผศ.ดร.</option>
                                <option value="อาจารย์">อาจารย์</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="advisor_other_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="advisor_other_firstname">
                        </div>
                        <div class="col form-group">
                            <label for="advisor_other_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="advisor_other_lastname">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="" for="coAdvisor">อาจารย์ที่ปรึกษาร่วม</label>
                        <select class="form-select" name="coAdvisor" id="coAdvisor" onchange="coAdvisorChange()">
                            <option value=""></option>
                            <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา">ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ">ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี">ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                            <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์">ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                            <option value="ดร. ปอลิน กองสุวรรณ">ดร.ปอลิน กองสุวรรณ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล">ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                            <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์">อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์">ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                            <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา">รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม">ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                            <option value="ดร. พิชยพัชยา ศรีคร้าม">ดร.พิชยพัชยา ศรีคร้าม</option>
                            <option value="other">อื่น ๆ</option>
                        </select>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3" id="coAdvisor_other" hidden>
                        <div class="col form-group">
                            <label for="coAdvisor_other_prefix">คำนำหน้า</label>
                            <select class="form-select" name="coAdvisor_other_prefix">
                                <option value=""></option>
                                <option value="ดร.">ดร.</option>
                                <option value="ศ.">ศ.</option>
                                <option value="ศ.ดร.">ศ.ดร.</option>
                                <option value="รศ.">รศ.</option>
                                <option value="รศ.ดร.">รศ.ดร.</option>
                                <option value="ผศ.">ผศ.</option>
                                <option value="ผศ.ดร.">ผศ.ดร.</option>
                                <option value="อาจารย์">อาจารย์</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="coAdvisor_other_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="coAdvisor_other_firstname">
                        </div>
                        <div class="col form-group">
                            <label for="coAdvisor_other_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="coAdvisor_other_lastname">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="" for="chairman">ประธานกรรมการ</label>
                        <select class="form-select" name="chairman" id="chairman" onchange="chairmanChange()" required>
                            <option value=""></option>
                            <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา">ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ">ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี">ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                            <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์">ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                            <option value="ดร. ปอลิน กองสุวรรณ">ดร.ปอลิน กองสุวรรณ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล">ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                            <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์">อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์">ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                            <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา">รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม">ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                            <option value="ดร. พิชยพัชยา ศรีคร้าม">ดร.พิชยพัชยา ศรีคร้าม</option>
                            <option value="other">อื่น ๆ</option>
                        </select>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3" id="chairman_other" hidden>
                        <div class="col form-group">
                            <label for="chairman_other_prefix">คำนำหน้า</label>
                            <select class="form-select" name="chairman_other_prefix">
                                <option value=""></option>
                                <option value="ดร.">ดร.</option>
                                <option value="ศ.">ศ.</option>
                                <option value="ศ.ดร.">ศ.ดร.</option>
                                <option value="รศ.">รศ.</option>
                                <option value="รศ.ดร.">รศ.ดร.</option>
                                <option value="ผศ.">ผศ.</option>
                                <option value="ผศ.ดร.">ผศ.ดร.</option>
                                <option value="อาจารย์">อาจารย์</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="chairman_other_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="chairman_other_firstname">
                        </div>
                        <div class="col form-group">
                            <label for="chairman_other_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="chairman_other_lastname">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="" for="director1">กรรมการคนที่ 1</label>
                        <select class="form-select" name="director1" id="director1" onchange="director1Change(this)" required>
                            <option value=""></option>
                            <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา">ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ">ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี">ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                            <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์">ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                            <option value="ดร. ปอลิน กองสุวรรณ">ดร.ปอลิน กองสุวรรณ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล">ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                            <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์">อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์">ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                            <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา">รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม">ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                            <option value="ดร. พิชยพัชยา ศรีคร้าม">ดร.พิชยพัชยา ศรีคร้าม</option>
                            <option value="other">อื่น ๆ</option>
                        </select>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3" id="director1_other" hidden>
                        <div class="col form-group">
                            <label for="director1_other_prefix">คำนำหน้า</label>
                            <select class="form-select" name="director1_other_prefix">
                                <option value=""></option>
                                <option value="ดร.">ดร.</option>
                                <option value="ศ.">ศ.</option>
                                <option value="ศ.ดร.">ศ.ดร.</option>
                                <option value="รศ.">รศ.</option>
                                <option value="รศ.ดร.">รศ.ดร.</option>
                                <option value="ผศ.">ผศ.</option>
                                <option value="ผศ.ดร.">ผศ.ดร.</option>
                                <option value="อาจารย์">อาจารย์</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="director1_other_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="director1_other_firstname">
                        </div>
                        <div class="col form-group">
                            <label for="director1_other_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="director1_other_lastname">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="" for="director2">กรรมการคนที่ 2</label>
                        <select class="form-select" name="director2" id="director2" onchange="director2Change()" required>
                            <option value=""></option>
                            <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา">ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ">ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี">ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                            <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์">ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                            <option value="ดร. ปอลิน กองสุวรรณ">ดร.ปอลิน กองสุวรรณ</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล">ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                            <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์">อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                            <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์">ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                            <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา">รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                            <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม">ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                            <option value="ดร. พิชยพัชยา ศรีคร้าม">ดร.พิชยพัชยา ศรีคร้าม</option>
                            <option value="other">อื่น ๆ</option>
                        </select>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3" id="director2_other" hidden>
                        <div class="col form-group">
                            <label for="director2_other_prefix">คำนำหน้า</label>
                            <select class="form-select" name="director2_other_prefix">
                                <option value=""></option>
                                <option value="ดร.">ดร.</option>
                                <option value="ศ.">ศ.</option>
                                <option value="ศ.ดร.">ศ.ดร.</option>
                                <option value="รศ.">รศ.</option>
                                <option value="รศ.ดร.">รศ.ดร.</option>
                                <option value="ผศ.">ผศ.</option>
                                <option value="ผศ.ดร.">ผศ.ดร.</option>
                                <option value="อาจารย์">อาจารย์</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="director2_other_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="director2_other_firstname">
                        </div>
                        <div class="col form-group">
                            <label for="director2_other_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="director2_other_lastname">
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <div class="col-form-label">ปีที่อนุมัติเล่มปริญญานิพนธ์</div>
                        </div>
                        <div class="col-auto">
                            <select name="semester" class="form-select" required>
                                <option value=""></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="col-form-label">/</div>
                        </div>
                        <div class="col-auto">
                            <input class="form-control" type="number" name="approval_year" maxlength="4" required>
                        </div>

                    </div>

                    <div class="form-group row align-items-center mb-3">
                        <div class="col-auto">
                            <div class="col-form-label">ปีที่ตีพิมพ์เล่ม</div>
                        </div>
                        <div class="col-auto">
                            <input class="form-control" type="number" name="printed_year" required>
                        </div>
                    </div>

                    <div class="form-group" id="formWord">
                        <label for="">คำสำคัญ (อย่างน้อย 3 คำ)</label>
                        <input class="form-control mb-3" type="text" name="keyword[]" id="keyword_1" placeholder="คำสำคัญ 1" required>
                        <input class="form-control mb-3" type="text" name="keyword[]" id="keyword_2" placeholder="คำสำคัญ 2" required>
                        <input class="form-control mb-3" type="text" name="keyword[]" id="keyword_3" placeholder="คำสำคัญ 3" required>
                    </div>
                    <div class="mb-3">
                        <input class="btn btn-success" type="button" value="+" id="buttonAddWord">
                        <input class="btn btn-danger" type="button" value="-" id="buttonDeleteWord">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">ไฟล์หน้าอนุมัติ (ชนิดไฟล์ PDF)</label>
                        <input class="form-control" type="file" name="approval_file" accept="application/pdf" onchange="checkPDF(event)">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">ไฟล์เล่มปริญญานิพนธ์ (ชนิดไฟล์ PDF)</label>
                        <input class="form-control" type="file" name="thesis_file" accept="application/pdf" onchange="checkPDF(event)">
                    </div>

                    <div class="mb-4">
                        <label for="" class="form-label">ไฟล์โปสเตอร์ (ชนิดไฟล์ PDF)</label>
                        <input class="form-control" type="file" name="poster_file" accept="application/pdf" onchange="checkPDF(event)">
                    </div>
                    <input class="btn btn-primary container-fluid mb-4" type="submit" value="เพิ่มข้อมูล" name="submitAddThesis" onclick="checkInput()">
                </form>
            </div>

            <!-- tab csv -->
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="1">
                <div class="container mt-5">
                    <form action="./csv_thesis_add.php" method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="form-group">
                                <label for="csvFile" class="mr-2">เลือกไฟล์ CSV</label>
                                <input type="file" id="csvFile" name="csvFile" class="form-control-file" accept=".csv" onchange="checkCSV(event)">
                            </div>
                            <div>
                                <a class="btn btn-outline-warning" href="./src/thesis_templete.csv" download>Templete CSV</a>
                            </div>
                        </div>
                        <!-- table show csv upload -->
                        <div id="areaTable" style="overflow-x:auto; overflow-y:auto; max-height:500px; margin:1.5rem 0;">
                            <table class="table table-bordered" id="tableCSV">

                            </table>
                        </div>
                        <div>
                            <labe for="fileDirectory">เลือกโฟลเดอร์ไฟล์ PDF</labe>
                            <input class="btn mb-3" type="file" name="fileDirectory[]" id="fileDirectory" webkitdirectory multiple>
                        </div>
                        <input type="submit" class="btn btn-primary mb-3 center" value="Upload" name="submitBtn" id="submitCsvBtn" style="visibility: hidden;">
                    </form>
                </div>

            </div>

        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        let buttonAddWord = document.getElementById('buttonAddWord');
        let buttonDeleteWord = document.getElementById('buttonDeleteWord');
        let formWord = document.getElementById('formWord');
        let i = 4;
        buttonAddWord.addEventListener('click', () => {
            $html = '<input class="form-control mb-3" type="text" name="keyword[]" id="keyword_' + i + '" placeholder="คำสำคัญ ' + i + '" required>';
            formWord.insertAdjacentHTML('beforeend', $html);
            i++;
            console.log('i=', i);
        })
        buttonDeleteWord.addEventListener('click', () => {
            console.log('click');
            if (i > 4) {
                let id = 'keyword_' + (i - 1);
                console.log(id);
                console.log(document.querySelector(id));
                let currentDelete = document.getElementById(id);
                currentDelete.remove();
                i--;
            }
        })

        const advisorChange = () => {
            let advisorDOM = document.getElementById('advisor');
            if (advisorDOM.value === 'other') {
                document.getElementById('advisor_other').hidden = false;
            } else {
                document.getElementById('advisor_other').hidden = true;
            }
        }

        const coAdvisorChange = () => {
            let advisorDOM = document.getElementById('coAdvisor');
            if (advisorDOM.value === 'other') {
                document.getElementById('coAdvisor_other').hidden = false;
            } else {
                document.getElementById('coAdvisor_other').hidden = true;
            }
        }

        const chairmanChange = () => {
            let advisorDOM = document.getElementById('chairman');
            if (advisorDOM.value === 'other') {
                document.getElementById('chairman_other').hidden = false;
            } else {
                document.getElementById('chairman_other').hidden = true;
            }
        }

        const director1Change = () => {
            if (document.getElementById('director1').value === 'other') {
                document.getElementById('director1_other').hidden = false;
            } else {
                document.getElementById('director1_other').hidden = true;
            }
        }

        const director2Change = () => {
            if (document.getElementById('director2').value === 'other') {
                document.getElementById('director2_other').hidden = false;
            } else {
                document.getElementById('director2_other').hidden = true;
            }
        }

        const checkPDF = (event) => {
            let file = event.target.files;
            if (file[0].type != 'application/pdf') {
                event.target.value = '';
            } else {
                // console.log('pdf');
            }
        }

        const checkCSV = (event) => {
            let file = event.target.files;
            if (file[0].type != 'text/csv') {
                event.target.value = '';
            } else {
                //have file csv
                let areaTable = document.querySelector('#areaTable');
                let tableCSV = document.querySelector('#tableCSV');
                let submitCsvBtn = document.querySelector('#submitCsvBtn');
                submitCsvBtn.style.visibility = "visible";

                let reader = new FileReader();
                reader.readAsText(file[0]);

                reader.onload = function() {
                    let csv = reader.result;
                    console.log("csv", csv);
                    let rows = csv.split("\n");
                    let header = rows[0];
                    let tableHTML = ""

                    rows.forEach((row) => {
                        console.log("row", row)
                        row = row.replaceAll(", ", " ");
                        row = row.replaceAll("\"", " ");
                        // row  = row.replaceAll(/\t/g, " ");
                        console.log("row replace", row)
                        let column = row.split(",");
                        if (row === header) {
                            console.log("header equal row")
                            tableHTML += "<thead>"
                            tableHTML += "<tr>";
                            column.forEach((col) => {
                                console.log("col", col)
                                tableHTML += `<th>${col}</th>`;
                            })
                            tableHTML += "</tr>";
                            tableHTML += "</thead>"
                            return
                        }
                        if (row == '') {
                            return
                        }
                        tableHTML += "<tr>";
                        column.forEach((col) => {
                            console.log("col", col)
                            tableHTML += `<td style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>${col}</td>`;
                        })
                        tableHTML += "</tr>";
                    })
                    console.log(tableHTML)
                    tableCSV.innerHTML = tableHTML;
                };
                reader.onerror = function() {
                    console.log(reader.error);
                };

            }
        }

        const checkMemberReq = (checkInput) => {
            let member_id = checkInput.name + '_id';
            let member_prefix = checkInput.name + '_prefix';
            let member_firstname = checkInput.name + '_firstname';
            let member_lastname = checkInput.name + '_lastname';
            document.getElementById(member_id).toggleAttribute('required');
            document.getElementById(member_prefix).toggleAttribute('required');
            document.getElementById(member_firstname).toggleAttribute('required');
            document.getElementById(member_lastname).toggleAttribute('required');
        }

        //check members_id RegEx
        let members_id = document.querySelectorAll('.members_id');
        members_id.forEach((member) => {
            member.addEventListener("keyup", () => {
                const x = member.value.replace(/\D/g, '').match(/^(\d{12})(\d{1})$/)
                member.value = (x[1] + '-' + x[2])

            });
        });

        //submit csv
        $('#submitCsvBtn').on('click', () => {

        })
    </script>
    <script type="text/javascript">
        $(function() {
            function isEnglish(text) {
                // Use a regular expression to check if the text contains only English characters
                return /^[a-zA-Z\s]*$/.test(text);
            }

            // Move checkInput function outside of the scope to make it globally accessible
            window.checkInput = function(inputText) {
                if (isEnglish(inputText)) {
                    // Alert that the input is in English
                    // alert("Input is in English!");
                } else {
                    // Alert that the input is not in English
                    // alert("Input is not in English!");
                }
            };

            // Add event listener to the form submission
            $("#myform1").on("submit", function(event) {
                var form = $(this)[0];
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');

                // Retrieve input text and call checkInput function
                var inputText = $("#textInput").val();
                checkInput(inputText);
            });
        });
    </script>


</body>

</html>