<?php
require "dbconnect.php";


if (isset($_GET['advisor'])) {
    $advisor = $_GET['advisor'];
    $advisor = explode("_", $advisor);

    $prefix_advisor = $advisor[0];
    $name_advisor = $advisor[1];
    $surname_advisor = $advisor[2];

    $searchSelect = 'advisor';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMUTT</title>
</head>

<body>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <?php require "template/header.php"; ?>

    <div class='container d-flex flex-column my-5 gap-3'>
        <div class="d-flex my-3">
            <select name="" id="" class="form-select rounded-0 w-25">
                <option value="all" <?php if ($searchSelect == 'all') {
                                        echo "selected";
                                    } ?>>ทั้งหมด</option>
                <option value="thesis_name" <?php if ($searchSelect == 'thesis_name') {
                                                echo "selected";
                                            } ?>>ชื่อปริญญานิพนธ์</option>
                <option value="keyword" <?php if ($searchSelect == 'keyword') {
                                            echo "selected";
                                        } ?>>คำสำคัญ</option>
                <option value="printed_year" <?php if ($searchSelect == 'printed_year') {
                                                    echo "selected";
                                                } ?>>ปีตีพิมพ์เล่ม</option>
                <option value="semester" <?php if ($searchSelect == 'semester') {
                                                echo "selected";
                                            } ?>>ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                <option value="abstract" <?php if ($searchSelect == 'abstract') {
                                                echo "selected";
                                            } ?>>บทคัดย่อ</option>
                <option value="author <?php if ($searchSelect == 'author') {
                                            echo "selected";
                                        } ?>">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                <option value="advisor" <?php if ($searchSelect == 'advisor') {
                                            echo "selected";
                                        } ?>>ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
            </select>

            <input type="search" name="" id="" class="form-control rounded-0 flex-grow-1" value="<?php echo $prefix_advisor . $name_advisor . " " . $surname_advisor; ?>">
            <button class="btn btn-outline-secondary rounded-0 col-auto"><i class="bi bi-search px-1"></i>ค้นหา</button>
        </div>

        <?php
        try {
            $insert_thesis = $conn->prepare("SELECT * FROM thesis_document WHERE prefix_advisor = :prefix_advisor AND name_advisor = :name_advisor AND surname_advisor = :surname_advisor");
            $insert_thesis->bindParam(":prefix_advisor", $prefix_advisor);
            $insert_thesis->bindParam(":name_advisor", $name_advisor);
            $insert_thesis->bindParam(":surname_advisor", $surname_advisor);
            $insert_thesis->execute();
            $result = $insert_thesis->fetchAll(PDO::FETCH_ASSOC);
            if ($insert_thesis->rowCount() > 0) {
                foreach ($result as $row) {
                    echo "<div class='border border-dark w-100 p-3 d-flex flex-column'>
                    <a class='text-dark' id='thesisName' href='thesis?id=$row[thesis_id]'>
                    <div class='fw-bold'>$row[thai_name]</div>
                    <div class='fw-bold'>$row[english_name]</div>
                    </a>
                    <div>คณะผู้จัดทำ ";

                    $selectMem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = $row[thesis_id]");
                    $selectMem->execute();
                    $result_selectMem = $selectMem->fetchAll(PDO::FETCH_ASSOC);

                    if ($selectMem->rowCount() > 0) {
                        $count = count($result_selectMem);
                        $i = 1;
                        foreach ($result_selectMem as $mem) {
                            $nameAuthor = $mem['prefix'] . "" . $mem['name'] . " " . $mem['lastname'];
                            echo "<div class='d-inline'>$nameAuthor</div>";
                            if ($count != $i++) {
                                echo "<span class='text-dark'>, </span>";
                            }
                        }
                    }
                    echo "</div>";
                    echo "<div>อาจารยที่ปรึกษา <a href='#' class='link-primary' style='text-decoration:none;'>$row[prefix_advisor] $row[name_advisor] $row[surname_advisor]</a>";
                    if ($row['prefix_coAdvisor'] != '') {
                        echo ", ";
                        echo "<a href='#' class='link-primary' style='text-decoration:none;'>$row[prefix_coAdvisor] $row[name_coAdvisor] $row[surname_coAdvisor]</a>";
                    }
                    echo "</div>";

                    echo "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>$row[keyword]</a></div>";
                    echo "<div>ปีที่พิมพ์เล่ม <a href='#' class='link-primary' style='text-decoration:none;'>$row[printed_year]</a></div>";
                    echo "</div>";
                }
            } else {
                echo "ไม่พบข้อมูล";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        ?>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>