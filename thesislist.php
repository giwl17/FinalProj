<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMUTT</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

</head>

<body>
    <?php require "template/header.php"; ?>

    <div class='container d-flex flex-column my-5 gap-3'>
        <div class="d-flex my-3">
            <select name="" id="" class="form-select rounded-0 w-25">
                <option value="all" selected>ทั้งหมด</option>
                <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                <option value="keyword">คำสำคัญ</option>
                <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                <option value="abstract">บทคัดย่อ</option>
                <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
            </select>

            <input type="search" name="" id="" class="form-control rounded-0 flex-grow-1">
            <button class="btn btn-outline-secondary rounded-0 col-auto"><i class="bi bi-search px-1"></i>ค้นหา</button>
        </div>

    <?php require "thesislist_db.php"  ?>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>