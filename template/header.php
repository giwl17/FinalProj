<?php
session_start();
$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];
$role = "";
if (isset($_SESSION['role'])) { 
    $name = $_SESSION['name'] . "&nbsp" . $_SESSION['lastname'];
    if ($_SESSION['role'] == 1) {
        $role = "ผู้ดูแลระบบ";
    } elseif ($_SESSION['role'] == 2) {
        $role = "เจ้าหน้าที่";
    } elseif ($_SESSION['role'] == 3) {
        $role = "เจ้าหน้าที่ชั่วคราว";
    } elseif ($_SESSION['role'] == 4) {
        $role = "อาจารย์";
    } elseif ($_SESSION['role'] == 5) {
        $role = "นักศึกษา";
    } else {
        // header("Location: login.php");
        // exit();
    }
}
?>

<div class="container-fluid d-flex flex-column px-0 shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-5 py-3">
        <div class="d-flex align-items-center">
            <a href="/FinalProj/" class="navbar-brand"><img class="" src="/FinalProj/img/rmuttlogo.png" alt="rmutt" height="65"></a>
            <div class="mx-3">
                <p class="h4 fw-bold text-dark d-none d-sm-block">จัดการเล่มปริญญานิพนธ์</p>
                <p class="h6 text-secondary d-none d-sm-block">ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มหาวิทยาลัยเทคโนโลยีราชมงคลธัญบุรี</p>
            </div>
        </div>
        <?php if (isset($_SESSION['role'])) : ?>
            <div class="btn-group">
                <a type="button" class="btn btn-outline-light text-dark" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <?= $name ?> || <?= $role ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg-end">
                    <li><a href="logout.php" class="dropdown-item" type="button">Logout</a></li>
                </ul>
            </div>
        <?php else : ?>
            <div>
                <a href="login" style="text-decoration:none; color:#333333;">เข้าสู่ระบบ</a>
            </div>
        <?php endif; ?>

    </div>
    <nav class="navbar navbar-expand-sm justify-content-center bg-light px-5 py-3">
        <div class="container-fluid p-0 justify-content-sm-end justify-content-center">
            <?php
            if ($role == "นักศึกษา") {
                // $role = "นักศึกษา";
                echo "<ul class='navbar-nav'>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/'>รายการปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/chart_director'>สถิติข้อมูล</a></li>
            </ul>";
            } elseif ($role == "อาจารย์") {
                // $role = "อาจารย์";
                echo "<ul class='navbar-nav'>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/'>รายการปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/chart_director'>สถิติข้อมูล</a></li>
            </ul>";
            } elseif ($role == "เจ้าหน้าที่ชั่วคราว") {
                // $role = "เจ้าหน้าที่ชั่วคราว";
                echo "<ul class='navbar-nav'>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/'>รายการปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/thesislistwaiting'>รายการที่รอตรวจสอบข้อมูล</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/thesisadd'>เพิ่มข้อมูลปริญญานิพนธ์</a></li>
            </ul>";
            } elseif ($role == "เจ้าหน้าที่") {
                // $role = "เจ้าหน้าที่";
                echo "<ul class='navbar-nav'>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/'>รายการปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/thesislistwaiting'>รายการที่รอตรวจสอบข้อมูล</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/chart_director'>สถิติข้อมูล</a></li>
            </ul>";
            } elseif ($role == "ผู้ดูแลระบบ") {
                // $role = "ผู้ดูแลระบบ";
                echo "<ul class='navbar-nav'>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/'>รายการปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/thesislistwaiting'>รายการที่รอตรวจสอบข้อมูล</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/thesisadd'>เพิ่มข้อมูลปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/thesisdelete'>ลบปริญญานิพนธ์</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/manage_privilege'>จัดการสิทธิ์บัญชีผู้ใช้งาน</a></li>
                <li class='nav-item text-center dropdown'>
                    <a class='nav-link dropdown-toggle pe-0' href='#' data-bs-toggle='dropdown'>จัดการสมาชิก</a>
                    <ul class='dropdown-menu dropdown-menu-end'>
                        <li><a class='dropdown-item' href='/FinalProj/officeradd'>เพิ่มข้อมูลเจ้าหน้าที่</a></li>
                        <li><a class='dropdown-item' href='/FinalProj/temporaryadd'>เพิ่มข้อมูลเจ้าหน้าที่ชั่วคราว</a></li>
                        <li><a class='dropdown-item' href='/FinalProj/teacheradd'>เพิ่มข้อมูลอาจารย์</a></li>
                        <li><a class='dropdown-item' href='/FinalProj/studentadd'>เพิ่มข้อมูลนักศึกษา</a></li>
                    </ul>
                </li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/recycle_bin'>ถังขยะ</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/archive'>Archive</a></li>
                <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/chart_director'>สถิติข้อมูล</a></li>
            </ul>";
            } else {
                 echo "<ul class='navbar-nav'>
                 <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/'>รายการปริญญานิพนธ์</a></li>
                 <li class='nav-item text-center'><a class='nav-link' href='/FinalProj/chart_director'>สถิติข้อมูล</a></li>
             </ul>";
            }
            ?>
        </div>
    </nav>
</div>