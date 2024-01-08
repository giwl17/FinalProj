<?php
// session_start();
// if (!isset($_SESSION['email']) && !isset($_COOKIE['email'])) {
//     header("Location: login.php"); // ถ้าไม่มี session หรือ cookie ให้ redirect ไปที่หน้า login
//     exit();
// }
?>
<style type="text/css">
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .pull-right {
        float: right;
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .pull-right li {
        display: inline-block;
    }

    .pull-right li a {
        display: block;
        color: #333;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .pull-right li a:hover {
        background-color: #f1f1f1;
    }
</style>
<div class="container-fluid shadow-sm">
<div class="container-fluid shadow-sm">
    <div class="d-flex justify-content-between align-items-center px-5 py-3">
        <div class="d-flex align-items-center">
            <a href="/FinalProj/"><img class="" src="img/rmuttlogo.png" alt="rmutt" height="65"></a>
            <div class="mx-3">
                <p class="h4 fw-bold text-dark d-none d-sm-block">จัดการเล่มปริญญานิพนธ์</p>
                <p class="h6 text-secondary d-none d-sm-block">ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มหาวิทยาลัยเทคโนโลยีราชมงคลธัญบุรี</p>
            </div>
        </div>
        <div class="pull-right">
            <ul class="pull-right">
                <li class="dropdown">
                    <a href="#" class="dropbtn"><?php echo $_SESSION['name']; ?></a>
                    <ul class="dropdown-content">
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="icon-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
    <nav class="navbar navbar-expand-sm justify-content-center bg-light px-5 py-3">
        <div class="container-fluid p-0 justify-content-sm-end justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item text-center"><a class="nav-link" href="/FinalProj/">รายการปริญญานิพนธ์</a></li>
                <li class="nav-item text-center"><a class="nav-link" href="/FinalProj/thesislistwaiting">รายการที่รอตรวจสอบข้อมูล</a></li>
                <li class="nav-item text-center"><a class="nav-link" href="/FinalProj/thesisadd">เพิ่มข้อมูลปริญญานิพนธ์</a></li>
                <li class="nav-item text-center"><a class="nav-link" href="/FinalProj/thesisdelete">ลบปริญญานิพนธ์</a></li>
                <!-- <li class="nav-item text-center"><a class="nav-link" href="/FinalProj/member">จัดการสมาชิก</a></li> -->

                <li class="nav-item text-center dropdown">
                    <a class="nav-link dropdown-toggle pe-0" href="#" data-bs-toggle="dropdown">จัดการสมาชิก</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/FinalProj/officeradd">เพิ่มข้อมูลเจ้าหน้าที่</a></li>
                        <li><a class="dropdown-item" href="/FinalProj/temporaryadd">เพิ่มข้อมูลเจ้าหน้าที่ชั่วคราว</a></li>
                        <li><a class="dropdown-item" href="/FinalProj/teacheradd">เพิ่มข้อมูลอาจารย์</a></li>
                        <li><a class="dropdown-item" href="/FinalProj/studentadd">เพิ่มข้อมูลนักศึกษา</a></li>


                    </ul>
                </li>

                <li class="nav-item text-center dropdown">
                    <a class="nav-link dropdown-toggle pe-0" href="#" data-bs-toggle="dropdown">สถิติข้อมูล</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">สถิติการจัดเก็บเล่ม</a></li>
                        <li><a class="dropdown-item" href="/FinalProj/chart_director">สถิติการกำกับเล่ม</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</div>