<?php
session_start();
require "database.php";
$conn = new Database();

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    $result = $conn->selectThesis($id);
    if (count($result) != 0) {
        foreach ($result as $row) {
            if ($type === 'thesis') {
                $fileSelect =  $row['thesis_file'];
            } else if ($type === 'poster') {
                $fileSelect =  $row['poster_file'];
            } else if ($type === 'approval') {
                $fileSelect = $row['approval_file'];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเล่มปริญญานิพนธ์ : ดูไฟล์</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            height: 100vh;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column justify-content-center align-items-center h-100 bg-body-light shadow-sm">
        <?php if (isset($_SESSION['download_permissions'])) : ?>
            <?php if ($_SESSION['download_permissions'] == 1) : ?>
                <?php if ($type === 'thesis') : ?>
                    <div class="p-2 w-100 text-center">
                        <h1 class="h3">ไฟล์เล่มปริญญานิพนธ์</h1>
                        <a href="<? echo $fileSelect; ?>" class="btn btn-warning" download="<?php echo $fileSelect; ?>">ดาวน์โหลด</a>
                    </div>
                <?php elseif ($type === 'poster') : ?>
                    <div class="p-2 w-100 text-center">
                        <h1 class="h3">ไฟล์โปสเตอร์</h1>
                        <a href="<? echo $fileSelect; ?>" class="btn btn-warning" download="<?php echo $fileSelect; ?>">ดาวน์โหลด</a>
                    </div>
                <?php elseif ($type === 'approval') : ?>
                    <div class="p-2 w-100 text-center">
                        <h1 class="h3">ไฟล์อนุมัติ</h1>
                        <a href="<? echo $fileSelect; ?>" class="btn btn-warning" download="<?php echo $fileSelect; ?>">ดาวน์โหลด</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>

        <iframe src="<?php echo $fileSelect . "#toolbar=0"; ?>" width="100%" height="100%" loading="lazy"></iframe>
        <div class="position-absolute" style="font-size:100px; top:45%; left:33%; transform: rotate(-45deg); opacity:0.3;">RMUTT CPE</div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function disableMouseClick(e) {
            console.log(event);
        }

        function downloadFile() {
            alert("click");

        }
    </script>
</body>

</html>