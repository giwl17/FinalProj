<?php
require "dbconnect.php";
$id = $_GET['id'];

$select = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_id = :id");
$select->bindParam(":id", $id);
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);

$select_member = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :id");
$select_member->bindParam(":id", $id);
$select_member->execute();
$members = $select_member->fetchAll(PDO::FETCH_ASSOC);

function checkPrefixMembers($members)
{
    if ($members == 'นาย') {
        $html = "
        <option value=''></option>
        <option value='นาย' selected>นาย</option>
        <option value='นางสาว'>นางสาว</option>
        <option value='นาง'>นาง</option>";
        echo $html;
    } else if ($members == 'นางสาว') {
        $html = "
        <option value=''></option>
        <option value='นาย'>นาย</option>
        <option value='นางสาว' selected>นางสาว</option>
        <option value='นาง'>นาง</option>";
        echo $html;
    } else if ($members == 'นาง') {
        $html = "
        <option value=''></option>
        <option value='นาย'>นาย</option>
        <option value='นางสาว'>นางสาว</option>
        <option value='นาง' selected>นาง</option>";
        echo $html;
    } else {
        $html = "
        <option value='' selected></option>
        <option value='นาย'>นาย</option>
        <option value='นางสาว'>นางสาว</option>
        <option value='นาง' >นาง</option>";
        echo $html;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบข้อมูล</title>
    <link rel="icon" type="image/x-icon" href="../img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php require "template/header.php" ?>
    <form class="container mt-4" method="post" action="<?php echo $_SESSION['role'] == 3 ?  "../thesis_update_db.php" : "../thesis_check_detail_db.php" ?>" enctype="multipart/form-data" id="form" name="submitUpdateThesis">
        <?php echo $_SESSION['role'] == 3 ? "<h1 class='h3 text-primary text-center mb-4'>แก้ไขข้อมูล</h1>" : "<h1 class='h3 text-primary text-center mb-4'>ตรวจสอบข้อมูล</h1>"; ?>
        <div class="form-group mb-3">
            <label for="thesis_name_th">ชื่อปริญญานิพนธ์ (ภาษาไทย)</label>
            <textarea class="form-control required" name="thesis_name_th" cols="30" rows="4" style="resize: none;" required><?php echo $row['thai_name'] ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="thesis_name_en">ชื่อปริญญานิพนธ์ (ภาษาอังกฤษ)</label>
            <textarea class="form-control" name="thesis_name_en" cols="30" rows="4" style="resize: none;" required><?php echo $row['english_name'] ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="abstract">บทคัดย่อ</label>
            <textarea class="form-control" name="abstract" cols="30" rows="10" style="resize: none;" required><?php echo $row['abstract'] ?></textarea>
        </div>

        <div>
            <p>คณะผู้จัดทำ</p>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="member1" checked>
                <label class="form-check-label" for="member1">สมาชิกคนที่ 1</label>
                <div>
                    <div class="form-group mb-3">
                        <label for="member1_id">รหัสนักศึกษา</label>
                        <input class="form-control" type="text" name="member1_id" id="member1_id" value="<?php echo $members[0]['student_id'] ?? '' ?>" required>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                        <div class="col form-group">
                            <label for="member1_prefix">คำนำหน้า</label>
                            <select class="form-select" name="member1_prefix" required>
                                <?php checkPrefixMembers($members[0]['prefix']); ?>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="">ชื่อ</label>
                            <input class="form-control" type="text" name="member1_firstname" value="<?php echo $members[0]['name'] ?? '' ?>" required>
                        </div>
                        <div class="col form-group">
                            <label for="">นามสกุล</label>
                            <input class="form-control" type="text" name="member1_lastname" value="<?php echo $members[0]['lastname'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-check member">
                <?php
                if (($members[1]['student_id'] ?? '') == '') {
                    echo "<input class='form-check-input' type='checkbox' name='member2 onchange='checkMemberReq(this)'>
                    <label class='form-check-label' for='member2'>สมาชิกคนที่ 2</label>";
                } else {
                    echo "<input class='form-check-input' type='checkbox' name='member2' onchange='checkMemberReq(this)' checked>
                    <label class='form-check-label' for='member2'>สมาชิกคนที่ 2</label>";
                }
                ?>
                <div class="form-group mb-3 member-child">
                    <label for="member2_id">รหัสนักศึกษา</label>
                    <input class="form-control" type="text" name="member2_id" id="member2_id" value="<?php echo $members[1]['student_id'] ?? '' ?>">
                </div>
                <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                    <div class="col form-group">
                        <label for="member2_prefix">คำนำหน้า</label>
                        <select class="form-select" name="member2_prefix" id="member2_prefix">
                            <?php checkPrefixMembers($members[1]['prefix']) ?>
                        </select>
                    </div>
                    <div class="col form-group">
                        <label for="member2_firstname">ชื่อ</label>
                        <input class="form-control" type="text" name="member2_firstname" id="member2_firstname" value="<?php echo $members[1]['name'] ?? '' ?>">
                    </div>
                    <div class="col form-group">
                        <label for="member2_lastname">นามสกุล</label>
                        <input class="form-control" type="text" name="member2_lastname" id="member2_lastname" value="<?php echo $members[1]['lastname'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <div class="form-check">
                <?php
                if (($members[2]['student_id'] ?? '') == '') {
                    echo "<input class='form-check-input' type='checkbox' name='member3' onchange='checkMemberReq(this)'>
                    <label class='form-check-label' for='member3'>สมาชิกคนที่ 3</label>";
                } else {
                    echo "<input class='form-check-input' type='checkbox' name='member3' checked onchange='checkMemberReq(this)'>
                    <label class='form-check-label' for='member3' >สมาชิกคนที่ 3</label>";
                }
                ?>
                <div>
                    <div class="form-group mb-3">
                        <label for="member3_id">รหัสนักศึกษา</label>
                        <input class="form-control" type="text" name="member3_id" id="member3_id" value="<?php echo $members[2]['student_id'] ?? '' ?>">
                    </div>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                        <div class="col form-group">
                            <label for="member3_prefix">คำนำหน้า</label>
                            <select class="form-select" name="member3_prefix" id="member3_prefix">
                                <?php checkPrefixMembers($members[2]['prefix']) ?>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="member3_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="member3_firstname" id="member3_firstname" value="<?php echo $members[2]['name'] ?? '' ?>">
                        </div>
                        <div class="col form-group">
                            <label for="member3_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="member3_lastname" id="member3_lastname" value="<?php echo $members[1]['lastname'] ?? '' ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-3" id="formAdvisor">
            <label for="advisor">อาจารย์ที่ปรึกษาหลัก</label>
            <select class="form-select" name="advisor" id="advisor" onchange="advisorChange()">
                <option value=""></option>
                <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ มาโนช ประชา') {
                                                                    echo "selected";
                                                                } ?>>ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ') {
                                                                                echo "selected";
                                                                            } ?>>ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์') {
                                                                            echo "selected";
                                                                        } ?>>ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                <option value="ดร. ปอลิน กองสุวรรณ" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ดร. ปอลิน กองสุวรรณ') {
                                                        echo "selected";
                                                    } ?>>ดร.ปอลิน กองสุวรรณ</option>
                <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'อาจารย์ พัฒณ์รพี สุนันทพจน์') {
                                                                echo "selected";
                                                            } ?>>อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา') {
                                                                        echo "selected";
                                                                    } ?>>รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                <option value="ดร. พิชยพัชยา ศรีคร้าม" <?php if ($row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor'] == 'ดร. พิชยพัชยา ศรีคร้าม') {
                                                            echo "selected";
                                                        } ?>>ดร.พิชยพัชยา ศรีคร้าม</option>
                <option value="other" id='advisor_other_option'>อื่น ๆ</option>
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
                <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ มาโนช ประชา') {
                                                                    echo "selected";
                                                                } ?>>ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ') {
                                                                                echo "selected";
                                                                            } ?>>ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์') {
                                                                            echo "selected";
                                                                        } ?>>ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                <option value="ดร. ปอลิน กองสุวรรณ" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ดร. ปอลิน กองสุวรรณ') {
                                                        echo "selected";
                                                    } ?>>ดร.ปอลิน กองสุวรรณ</option>
                <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'อาจารย์ พัฒณ์รพี สุนันทพจน์') {
                                                                echo "selected";
                                                            } ?>>อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา') {
                                                                        echo "selected";
                                                                    } ?>>รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                <option value="ดร. พิชยพัชยา ศรีคร้าม" <?php if ($row['prefix_coAdvisor'] . " " . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'] == 'ดร. พิชยพัชยา ศรีคร้าม') {
                                                            echo "selected";
                                                        } ?>>ดร.พิชยพัชยา ศรีคร้าม</option>
                <option value="other" id="coAdvisor_other_option">อื่น ๆ</option>
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
            <select class="form-select" name="chairman" id="chairman" onchange="chairmanChange()">
                <option value=""></option>
                <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ มาโนช ประชา') {
                                                                    echo "selected";
                                                                } ?>>ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ') {
                                                                                echo "selected";
                                                                            } ?>>ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์') {
                                                                            echo "selected";
                                                                        } ?>>ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                <option value="ดร. ปอลิน กองสุวรรณ" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ดร. ปอลิน กองสุวรรณ') {
                                                        echo "selected";
                                                    } ?>>ดร.ปอลิน กองสุวรรณ</option>
                <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'อาจารย์ พัฒณ์รพี สุนันทพจน์') {
                                                                echo "selected";
                                                            } ?>>อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา') {
                                                                        echo "selected";
                                                                    } ?>>รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                <option value="ดร. พิชยพัชยา ศรีคร้าม" <?php if ($row['prefix_chairman'] . " " . $row['name_chairman'] . " " . $row['surname_chairman'] == 'ดร. พิชยพัชยา ศรีคร้าม') {
                                                            echo "selected";
                                                        } ?>>ดร.พิชยพัชยา ศรีคร้าม</option>
                <option value="other" id="chairman_other_option">อื่น ๆ</option>
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
            <select class="form-select" name="director1" id="director1" onchange="director1Change()">
                <option value=""></option>
                <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ มาโนช ประชา') {
                                                                    echo "selected";
                                                                } ?>>ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ') {
                                                                                echo "selected";
                                                                            } ?>>ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์') {
                                                                            echo "selected";
                                                                        } ?>>ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                <option value="ดร. ปอลิน กองสุวรรณ" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ดร. ปอลิน กองสุวรรณ') {
                                                        echo "selected";
                                                    } ?>>ดร.ปอลิน กองสุวรรณ</option>
                <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'อาจารย์ พัฒณ์รพี สุนันทพจน์') {
                                                                echo "selected";
                                                            } ?>>อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา') {
                                                                        echo "selected";
                                                                    } ?>>รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                <option value="ดร. พิชยพัชยา ศรีคร้าม" <?php if ($row['prefix_director1'] . " " . $row['name_director1'] . " " . $row['surname_director1'] == 'ดร. พิชยพัชยา ศรีคร้าม') {
                                                            echo "selected";
                                                        } ?>>ดร.พิชยพัชยา ศรีคร้าม</option>
                <option value="other" id="director1_other_option">อื่น ๆ</option>
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
            <select class="form-select" name="director2" id="director2" onchange="director2Change()">
                <option value=""></option>
                <option value="ผู้ช่วยศาสตราจารย์ มาโนช ประชา" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ มาโนช ประชา') {
                                                                    echo "selected";
                                                                } ?>>ผู้ช่วยศาสตราจารย์ มาโนช ประชา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ดร. ศิริชัย เตรียมล้ำเลิศ') {
                                                                                echo "selected";
                                                                            } ?>>ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ</option>
                <option value="ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี</option>
                <option value="ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์') {
                                                                            echo "selected";
                                                                        } ?>>ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์</option>
                <option value="ดร. ปอลิน กองสุวรรณ" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ดร. ปอลิน กองสุวรรณ') {
                                                        echo "selected";
                                                    } ?>>ดร.ปอลิน กองสุวรรณ</option>
                <option value="ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เดชรัชต์ ใจถวิล</option>
                <option value="อาจารย์ พัฒณ์รพี สุนันทพจน์" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'อาจารย์ พัฒณ์รพี สุนันทพจน์') {
                                                                echo "selected";
                                                            } ?>>อาจารย์พัฒณ์รพี สุนันทพจน์</option>
                <option value="ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ เจษฎา อรุณฤกษ์') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์เจษฎา อรุณฤกษ์</option>
                <option value="รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'รองศาสตราจารย์ดร. พฤศยน นินทนาวงศา') {
                                                                        echo "selected";
                                                                    } ?>>รองศาสตราจารย์ ดร.พฤศยน นินทนาวงศา</option>
                <option value="ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ผู้ช่วยศาสตราจารย์ดร. ธนสิน บุญนาม') {
                                                                        echo "selected";
                                                                    } ?>>ผู้ช่วยศาสตราจารย์ ดร.ธนสิน บุญนาม</option>
                <option value="ดร. พิชยพัชยา ศรีคร้าม" <?php if ($row['prefix_director2'] . " " . $row['name_director2'] . " " . $row['surname_director2'] == 'ดร. พิชยพัชยา ศรีคร้าม') {
                                                            echo "selected";
                                                        } ?>>ดร.พิชยพัชยา ศรีคร้าม</option>
                <option value="other" id="director2_other_option">อื่น ๆ</option>
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
                <select name="semester" class="form-select">
                    <option value="" <?php if ($row['semester'] == '') {
                                            echo "selected";
                                        } ?>></option>
                    <option value="1" <?php if ($row['semester'] == '1') {
                                            echo "selected";
                                        } ?>>1</option>
                    <option value="2" <?php if ($row['semester'] == '2') {
                                            echo "selected";
                                        } ?>>2</option>
                    <option value="3" <?php if ($row['semester'] == '3') {
                                            echo "selected";
                                        } ?>>3</option>
                </select>
            </div>
            <div class="col-auto">
                <div class="col-form-label">/</div>
            </div>
            <div class="col-auto">
                <input class="form-control" type="number" name="approval_year" maxlength="4" <?php echo "value=$row[approval_year]" ?>>
            </div>

        </div>

        <div class="form-group row align-items-center mb-3">
            <div class="col-auto">
                <div class="col-form-label">ปีที่ตีพิมพ์เล่ม</div>
            </div>
            <div class="col-auto">
                <input class="form-control" type="number" name="printed_year" <?php echo "value=$row[printed_year]" ?>>
            </div>
        </div>

        <div class="form-group" id="formWord">
            <label for="">คำสำคัญ (อย่างน้อย 3 คำ)</label>
            <?php
            $keywords = $row['keyword'];
            $keywords = explode(', ', $keywords);
            ?>
            <?php foreach ($keywords as $index => $keyword) : ?>
                <input class="form-control mb-3 keywords" type="text" name="keywords[<?= $index ?>]" id="keyword_<?= $index + 1 ?>" placeholder="คำสำคัญ<?= $index + 1 ?>" required <?php echo "value=$keyword ?? ''" ?>>
            <?php endforeach; ?>
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

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="d-flex gap-2 my-5 justify-content-center">
            <?php if ($_SESSION['role'] != 3) : ?>
                <div class="btn btn-danger" onclick="deleteThesis()">ลบข้อมูล</div>
                <div class="btn btn-primary" onclick="submitForm()">นำเข้าข้อมูล</div>
            <?php else : ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input class="btn btn-primary container-fluid mb-4" type="submit" value="แก้ไขข้อมูล" name="submitUpdateThesis">
            <?php endif; ?>
        </div>
    </form>

    <script>
        let buttonAddWord = document.getElementById('buttonAddWord');
        let buttonDeleteWord = document.getElementById('buttonDeleteWord');
        let formWord = document.getElementById('formWord');
        let keywordsInput = document.querySelectorAll(".keywords");
        let i = keywordsInput.length + 1;
        keywordsInput.forEach(keyword => {
            console.log(keyword.id, keyword.name);
        })
        buttonAddWord.addEventListener('click', () => {
            $html = '<input class="form-control mb-3" type="text" name="keywords[' + (i - 1) + ']" id="keyword_' + i + '" placeholder="คำสำคัญ ' + i + '" required>';
            formWord.insertAdjacentHTML('beforeend', $html);
            i++;
        })
        buttonDeleteWord.addEventListener('click', () => {
            if (i > 4) {
                let id = 'keyword_' + (i - 1);
                console.log(id);
                console.log(document.querySelector(id));
                let currentDelete = document.getElementById(id);
                currentDelete.remove();
                i--;
            }
        })

        //check advisor select other
        const advisorChange = () => {
            let advisorDOM = document.getElementById('advisor');
            if (advisorDOM.value === 'other') {
                document.getElementById('advisor_other').hidden = false;
            } else {
                document.getElementById('advisor_other').hidden = true;
            }
        }
        let teacher_other_option = document.querySelector("#advisor_other_option")
        let advisorSelect = document.querySelector("#advisor");
        let prefix_advisor = <?php echo json_encode($row['prefix_advisor']); ?>;
        let name_advisor = <?php echo json_encode($row['name_advisor']); ?>;
        let surname_advisor = <?php echo json_encode($row['surname_advisor']); ?>;
        if (prefix_advisor !== '' && advisorSelect.value == '') {
            teacher_other_option.selected = true
        }
        if (advisorSelect.value == 'other') {
            teacher_other_option.selected = true
            document.getElementById('advisor_other').hidden = false

            document.querySelector("[name='advisor_other_firstname']").value = name_advisor
            document.querySelector("[name='advisor_other_lastname']").value = surname_advisor
            if (prefix_advisor == "ศาสตราจารย์") {
                prefix_advisor = "ศ.";
            }
            if (prefix_advisor == "ศาสตราจารย์ ดร.") {
                prefix_advisor = "ศ.ดร.";
            }
            if (prefix_advisor == "รองศาสตราจารย์") {
                prefix_advisor = "รศ.";
            }
            if (prefix_advisor == "รองศาสตราจารย์ ดร.") {
                prefix_advisor = "รศ.ดร.";
            }
            if (prefix_advisor == "ผู้ช่วยศาสตราจารย์") {
                prefix_advisor = "ผศ.";
            }
            if (prefix_advisor == "ผู้ช่วยศาสตราจารย์ ดร.") {
                prefix_advisor = "ผศ.ดร.";
            }
            document.querySelector("[name='advisor_other_prefix']").value = prefix_advisor
        }


        const coAdvisorChange = () => {
            let advisorDOM = document.getElementById('coAdvisor');
            if (advisorDOM.value === 'other') {
                document.getElementById('coAdvisor_other').hidden = false;
            } else {
                document.getElementById('coAdvisor_other').hidden = true;
            }
        }
        let coAdvisor_other_option = document.querySelector("#coAdvisor_other_option")
        let coAdvisorSelect = document.querySelector("#coAdvisor");
        let prefix_coAdvisor = <?php echo json_encode($row['prefix_coAdvisor']); ?>;
        let name_coAdvisor = <?php echo json_encode($row['name_coAdvisor']); ?>;
        let surname_coAdvisor = <?php echo json_encode($row['surname_coAdvisor']); ?>;
        if (prefix_coAdvisor !== '' && coAdvisorSelect.value == '') {
            coAdvisor_other_option.selected = true
        }
        if (coAdvisorSelect.value == 'other') {
            coAdvisor_other_option.selected = true
            document.getElementById('coAdvisor_other').hidden = false

            document.querySelector("[name='coAdvisor_other_firstname']").value = name_coAdvisor
            document.querySelector("[name='coAdvisor_other_lastname']").value = surname_coAdvisor
            if (prefix_coAdvisor == "ศาสตราจารย์") {
                prefix_coAdvisor = "ศ.";
            }
            if (prefix_coAdvisor == "ศาสตราจารย์ ดร.") {
                prefix_coAdvisor = "ศ.ดร.";
            }
            if (prefix_coAdvisor == "รองศาสตราจารย์") {
                prefix_coAdvisor = "รศ.";
            }
            if (prefix_coAdvisor == "รองศาสตราจารย์ ดร.") {
                prefix_coAdvisor = "รศ.ดร.";
            }
            if (prefix_coAdvisor == "ผู้ช่วยศาสตราจารย์") {
                prefix_coAdvisor = "ผศ.";
            }
            if (prefix_coAdvisor == "ผู้ช่วยศาสตราจารย์ ดร.") {
                prefix_coAdvisor = "ผศ.ดร.";
            }
            document.querySelector("[name='coAdvisor_other_prefix']").value = prefix_coAdvisor
        }

        const chairmanChange = () => {
            let advisorDOM = document.getElementById('chairman');
            if (advisorDOM.value === 'other') {
                document.getElementById('chairman_other').hidden = false;
            } else {
                document.getElementById('chairman_other').hidden = true;
            }
        }
        let chairman_other_option = document.querySelector("#chairman_other_option")
        let chairmanSelect = document.querySelector("#chairman");
        let prefix_chairman = <?php echo json_encode($row['prefix_chairman']); ?>;
        let name_chairman = <?php echo json_encode($row['name_chairman']); ?>;
        let surname_chairman = <?php echo json_encode($row['surname_chairman']); ?>;
        if (prefix_chairman !== '' && chairmanSelect.value == '') {
            chairman_other_option.selected = true
        }
        if (chairmanSelect.value == 'other') {
            chairman_other_option.selected = true
            document.getElementById('chairman_other').hidden = false

            document.querySelector("[name='chairman_other_firstname']").value = name_chairman
            document.querySelector("[name='chairman_other_lastname']").value = surname_chairman
            if (prefix_chairman == "ศาสตราจารย์") {
                prefix_chairman = "ศ.";
            }
            if (prefix_chairman == "ศาสตราจารย์ ดร.") {
                prefix_chairman = "ศ.ดร.";
            }
            if (prefix_chairman == "รองศาสตราจารย์") {
                prefix_chairman = "รศ.";
            }
            if (prefix_chairman == "รองศาสตราจารย์ ดร.") {
                prefix_chairman = "รศ.ดร.";
            }
            if (prefix_chairman == "ผู้ช่วยศาสตราจารย์") {
                prefix_chairman = "ผศ.";
            }
            if (prefix_chairman == "ผู้ช่วยศาสตราจารย์ ดร.") {
                prefix_chairman = "ผศ.ดร.";
            }
            document.querySelector("[name='chairman_other_prefix']").value = prefix_chairman
        }

        const director1Change = () => {
            if (document.getElementById('director1').value === 'other') {
                document.getElementById('director1_other').hidden = false;
            } else {
                document.getElementById('director1_other').hidden = true;
            }
        }
        let director1_other_option = document.querySelector("#director1_other_option")
        let director1Select = document.querySelector("#director1");
        let prefix_director1 = <?php echo json_encode($row['prefix_director1']); ?>;
        let name_director1 = <?php echo json_encode($row['name_director1']); ?>;
        let surname_director1 = <?php echo json_encode($row['surname_director1']); ?>;
        if (prefix_director1 !== '' && director1Select.value == '') {
            director1_other_option.selected = true
        }
        if (director1Select.value == 'other') {
            director1_other_option.selected = true
            document.getElementById('director1_other').hidden = false

            document.querySelector("[name='director1_other_firstname']").value = name_director1
            document.querySelector("[name='director1_other_lastname']").value = surname_director1
            if (prefix_director1 == "ศาสตราจารย์") {
                prefix_director1 = "ศ.";
            }
            if (prefix_director1 == "ศาสตราจารย์ ดร.") {
                prefix_director1 = "ศ.ดร.";
            }
            if (prefix_director1 == "รองศาสตราจารย์") {
                prefix_director1 = "รศ.";
            }
            if (prefix_director1 == "รองศาสตราจารย์ ดร.") {
                prefix_director1 = "รศ.ดร.";
            }
            if (prefix_director1 == "ผู้ช่วยศาสตราจารย์") {
                prefix_director1 = "ผศ.";
            }
            if (prefix_director1 == "ผู้ช่วยศาสตราจารย์ ดร.") {
                prefix_director1 = "ผศ.ดร.";
            }
            document.querySelector("[name='director1_other_prefix']").value = prefix_director1
        }

        const director2Change = () => {
            if (document.getElementById('director2').value === 'other') {
                document.getElementById('director2_other').hidden = false;
            } else {
                document.getElementById('director2_other').hidden = true;
            }
        }
        let director2_other_option = document.querySelector("#director2_other_option")
        let director2Select = document.querySelector("#director2");
        let prefix_director2 = <?php echo json_encode($row['prefix_director2']); ?>;
        let name_director2 = <?php echo json_encode($row['name_director2']); ?>;
        let surname_director2 = <?php echo json_encode($row['surname_director2']); ?>;
        if (prefix_director2 !== '' && director2Select.value == '') {
            director2_other_option.selected = true
        }
        if (director2Select.value == 'other') {
            director2_other_option.selected = true
            document.getElementById('director2_other').hidden = false

            document.querySelector("[name='director2_other_firstname']").value = name_director2
            document.querySelector("[name='director2_other_lastname']").value = surname_director2
            if (prefix_director2 == "ศาสตราจารย์") {
                prefix_director2 = "ศ.";
            }
            if (prefix_director2 == "ศาสตราจารย์ ดร.") {
                prefix_director2 = "ศ.ดร.";
            }
            if (prefix_director2 == "รองศาสตราจารย์") {
                prefix_director2 = "รศ.";
            }
            if (prefix_director2 == "รองศาสตราจารย์ ดร.") {
                prefix_director2 = "รศ.ดร.";
            }
            if (prefix_director2 == "ผู้ช่วยศาสตราจารย์") {
                prefix_director2 = "ผศ.";
            }
            if (prefix_director2 == "ผู้ช่วยศาสตราจารย์ ดร.") {
                prefix_director2 = "ผศ.ดร.";
            }
            document.querySelector("[name='director2_other_prefix']").value = prefix_director2
        }

        const checkPDF = (event) => {
            let file = event.target.files;
            if (file[0].type != 'application/pdf') {
                event.target.value = '';
            } else {
                // console.log('pdf');
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

        function deleteThesis() {
            var formData = new FormData();
            formData.append('id', <?= $id ?>);
            Swal.fire({
                    title: 'ลบรายการ',
                    text: 'คุณต้องการลบรายการนี้หรือไม่',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ลบข้อมูล',
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        fetch("../deleteThesis.php", {
                            body: formData,
                            method: "POST"
                        }).then((res) => {
                            return res.text()
                        }).then(res => {
                            console.log(res)
                            Swal.fire('ลบรายการนี้สำเร็จ!', '', 'success')
                                .then(
                                    () => {
                                        location.replace("/FinalProj/thesislistwaiting");
                                    }
                                )
                        })
                    }
                })
        }

        function submitForm() {
            document.getElementById("form").submit();
        }
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>