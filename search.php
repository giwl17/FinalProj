<?php
require "dbconnect.php";


if (isset($_GET['advisor'])) {
    $advisor = $_GET['advisor'];
    $advisor = explode("_", $advisor);

    $prefix_advisor = $advisor[0];
    $name_advisor = $advisor[1];
    $surname_advisor = $advisor[2];

    $searchSelect = 'advisor';
} else if (isset($_GET['coAdvisor'])) {
    $coAdvisor = $_GET['coAdvisor'];

    $coAdvisor = explode("_", $coAdvisor);

    $prefix_advisor = $coAdvisor[0];
    $name_advisor = $coAdvisor[1];
    $surname_advisor = $coAdvisor[2];

    $searchSelect = 'advisor';
} else if (isset($_GET['printed'])) {
    $printed = $_GET['printed'];

    $searchSelect = 'printed_year';
} else if (isset($_GET['approval'])) {
    $approval = $_GET['approval'];

    $approvalEx = explode("/", $approval);
    $semester = $approvalEx[0];
    $approval_year = $approvalEx[1];

    $searchSelect = "semester";
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
        <div class="d-flex my-3 position-relative">
            <label class="position-absolute" style="top: -1.5rem;">ค้นหารายการจาก</label>

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

            <?php if ($searchSelect === 'advisor' or $searchSelect === 'coAdvisor') : ?>
                <input type="search" name="" id="" class="form-control rounded-0 flex-grow-1" value="<?php echo $prefix_advisor . $name_advisor . " " . $surname_advisor; ?>">
            <?php elseif ($searchSelect === 'printed_year') : ?>
                <input type="search" name="" id="" class="form-control rounded-0 flex-grow-1" value="<?php echo $printed; ?>">
            <?php elseif ($searchSelect === 'semester') : ?>
                <input type="search" name="" id="" class="form-control rounded-0 flex-grow-1" value="<?php echo $approval; ?>">
            <?php endif; ?>
            <button class="btn btn-outline-secondary rounded-0 col-auto"><i class="bi bi-search px-1"></i>ค้นหา</button>
        </div>

        <?php
        try {
            if ($searchSelect === 'advisor' or $searchSelect === 'coAdvisor') {
                $sql = "SELECT * FROM thesis_document 
                WHERE (prefix_advisor = :prefix 
                AND name_advisor = :name 
                AND surname_advisor = :surname) 
                OR (prefix_coAdvisor = :prefix 
                AND name_coAdvisor = :name 
                AND surname_coAdvisor = :surname)";

                $insert_thesis = $conn->prepare($sql);
                $insert_thesis->bindParam(":prefix", $prefix_advisor);
                $insert_thesis->bindParam(":name", $name_advisor);
                $insert_thesis->bindParam(":surname", $surname_advisor);
            } else if ($searchSelect === 'printed_year') {
                $sql = "SELECT * FROM thesis_document
                WHERE printed_year = :printed";

                $insert_thesis = $conn->prepare($sql);
                $insert_thesis->bindParam(":printed", $printed);
            } else if ($searchSelect === 'semester') {
                $sql = "SELECT * FROM thesis_document
                WHERE semester = :semester AND approval_year = :approval_year";

                $insert_thesis = $conn->prepare($sql);
                $insert_thesis->bindParam(":semester" , $semester);
                $insert_thesis->bindParam(":approval_year" , $approval_year);
            }

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
                    $u = '_';
                    echo "</div>";
                    echo "<div>อาจารยที่ปรึกษา <a href='search?advisor=$row[prefix_advisor]$u$row[name_advisor]$u$row[surname_advisor]' class='link-primary' style='text-decoration:none;'>$row[prefix_advisor] $row[name_advisor] $row[surname_advisor]</a>";
                    if ($row['prefix_coAdvisor'] != '') {
                        echo ", ";
                        echo "<a href='search?coAdvisor=$row[prefix_coAdvisor]$u$row[name_coAdvisor]$u$row[surname_coAdvisor]' class='link-primary' style='text-decoration:none;'>$row[prefix_coAdvisor] $row[name_coAdvisor] $row[surname_coAdvisor]</a>";
                    }
                    echo "</div>";

                    echo "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>$row[keyword]</a></div>";
                    echo "<div>ปีที่พิมพ์เล่ม <a href='search?printed=$row[printed_year]' class='link-primary' style='text-decoration:none;'>$row[printed_year]</a></div>";
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