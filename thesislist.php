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

    <?php
    require_once "dbconnect.php";
    $select = "SELECT * FROM thesis_document WHERE thesis_status = 1 ORDER BY thesis_id DESC";
    $stmt = $conn->prepare($select);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    if ($stmt->rowCount() > 0) {
        foreach ($result as $row) {
            $html = "
                    <div class='border border-dark w-100 p-3 d-flex flex-column'>
                        <a class='text-dark' id='thesisName' href='thesis?id=$row->thesis_id'>
                        <div class='fw-bold'>$row->thai_name</div>
                        <div class='fw-bold'>$row->english_name</div>
                        </a>
                        <div>คณะผู้จัดทำ           
            ";

            $selectMem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = $row->thesis_id");
            $selectMem->execute();
            $result_selectMem = $selectMem->fetchAll(PDO::FETCH_OBJ);

            if ($selectMem->rowCount() > 0) {
                $count = count($result_selectMem);
                $i = 1;
                foreach ($result_selectMem as $mem) {
                    $nameAuthor = $mem->prefix . "" . $mem->name . " " . $mem->lastname;
                    $html .= "<div class='d-inline'>$nameAuthor</div>";
                    if ($count != $i++) {
                        $html .= "<span class='text-dark'>, </span>";
                    }
                }
            }
            $html .= "</div>";
            $html .= "<div>อาจารยที่ปรึกษา <a href='#' class='link-primary' style='text-decoration:none;'>$row->prefix_advisor $row->name_advisor $row->surname_advisor</a>";
            if($row->prefix_coAdvisor != '') {
                $html .= ", ";
                $html .= "<a href='#' class='link-primary' style='text-decoration:none;'>$row->prefix_coAdvisor $row->name_coAdvisor $row->surname_coAdvisor</a>";
            }
            $html .= "</div>";

            $html .= "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>$row->keyword</a></div>";
            $html .= "<div>ปีที่พิมพ์เล่ม <a href='#' class='link-primary' style='text-decoration:none;'>$row->printed_year</a></div>";
            $html .= "</div>";

            echo $html;
        }
      
    }

    ?>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>