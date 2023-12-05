<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มเล่มปริญญานิพนธ์</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require "template/header.php"; ?>
    <form class="container mt-4" method="post" action="">
        <h1 class="h3 text-primary text-center mb-4">เพิ่มข้อมูลปริญญานิพนธ์</h1>
        <div class="form-group mb-3">
            <label for="">ชื่อปริญญานิพนธ์ (ภาษาไทย)</label>
            <textarea class="form-control" name="" id="" cols="30" rows="4" style="resize: none;"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="">ชื่อปริญญานิพนธ์ (ภาษาอังกฤษ)</label>
            <textarea class="form-control" name="" id="" cols="30" rows="4" style="resize: none;"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="">บทคัดย่อ</label>
            <textarea class="form-control" name="" id="" cols="30" rows="10" style="resize: none;"></textarea>
        </div>

        <div>
            <p>คณะผู้จัดทำ</p>
            
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="" id="">
                <label class="form-check-label" for="">สมาชิกคนที่ 1</label>
            </div>

        </div>
        <input class="btn btn-primary container-fluid" type="submit" value="เพิ่มข้อมูล">
    </form>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>