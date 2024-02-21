<?php
require "dbconnect.php";

if (isset($_GET['selected']) && isset($_GET['data'])) {
    $searchSelect = $_GET['selected'];
    $dataInput = $_GET['data'];
    // echo $searchSelect . ' ' . $dataInput;
} else {
    if (isset($_GET['advisor'])) {
        $advisor = $_GET['advisor'];
        if (strpos($advisor, "_") !== false) {
            $advisor = explode("_", $advisor);
        } else {
            $advisor = explode(" ", $advisor);
        }

        $prefix_advisor = $advisor[0];
        $name_advisor = $advisor[1];
        $surname_advisor = $advisor[2];

        $searchSelect = 'advisor';
    } else if (isset($_GET['coAdvisor'])) {
        $coAdvisor = $_GET['coAdvisor'];
        if (strpos($coAdvisor, "_") !== false) {
            try {
                $coAdvisor = explode("_", $coAdvisor);
                $prefix_advisor = $coAdvisor[0];
                $name_advisor = $coAdvisor[1];
                $surname_advisor = $coAdvisor[2];
            } catch (Exception $e) {
                $prefix_advisor = "";
                $name_advisor = $coAdvisor[0];
                $surname_advisor = $coAdvisor[1];
            }
        } else {
            try {
                $coAdvisor = explode(" ", $coAdvisor);
                $prefix_advisor = $coAdvisor[0];
                $name_advisor = $coAdvisor[1];
                $surname_advisor = $coAdvisor[2];
            } catch (Exception $e) {
                $prefix_advisor = "";
                $name_advisor = $coAdvisor[0];
                $surname_advisor = $coAdvisor[1];
            }
        }
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
    } else if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $searchSelect = 'keyword';
    } else if (isset($_GET['abstract'])) {
        $abstract = $_GET['abstract'];
        $searchSelect = 'abstract';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเล่มปริญญานิพนธ์</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <?php require "template/header.php"; ?>

    <div class='container d-flex flex-column my-5 gap-3 position-relative'>
        <div class="d-flex flex-column">
            <form class="d-flex position-relative" action="search.php">
                <label class="position-absolute" style="top: -1.5rem;">ค้นหารายการจาก</label>
                <select name="selected" id="selectSearch" class="form-select rounded-0 w-25">
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
                    <option value="author" <?php if ($searchSelect == 'author') {
                                                echo "selected";
                                            } ?>>ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor" <?php if ($searchSelect == 'advisor') {
                                                echo "selected";
                                            } ?>>ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>

                <div class="flex-grow-1 position-relative">
                    <?php if (isset($_GET['selected'])) : ?>
                        <input type="search" name="data" id="inputSearch" class="form-control rounded-0 flex-grow-1" value="<?php echo $dataInput; ?>">
                    <?php else : ?>
                        <?php if ($searchSelect === 'advisor' or $searchSelect === 'coAdvisor') : ?>
                            <input type="search" name="data" id="inputSearch" class="form-control rounded-0 flex-grow-1" value="<?php echo $prefix_advisor . " " . $name_advisor . " " . $surname_advisor; ?>">
                        <?php elseif ($searchSelect === 'printed_year') : ?>
                            <input type="search" name="data" id="inputSearch" class="form-control rounded-0 flex-grow-1" value="<?php echo $printed; ?>">
                        <?php elseif ($searchSelect === 'semester') : ?>
                            <input type="search" name="data" id="inputSearch" class="form-control rounded-0 flex-grow-1" value="<?php echo $approval; ?>">
                        <?php elseif ($searchSelect === 'keyword') : ?>
                            <input type="search" name="data" id="inputSearch" class="form-control rounded-0 flex-grow-1" value="<?php echo $keyword; ?>">
                        <?php elseif ($searchSelect === 'abstract') : ?>
                            <input type="search" name="data" id="inputSearch" class="form-control rounded-0 flex-grow-1" value="<?php echo $abstract; ?>">
                        <?php endif; ?>
                    <?php endif; ?>


                    <div class="w-100 position-absolute d-none" id="searching"></div>
                </div>
                <button class="btn btn-outline-secondary rounded-0 col-auto"><i class="bi bi-search px-1"></i>ค้นหา</button>
            </form>
            <a href='/FinalProj/search/advance' class="text-end mt-2 link-dark">การค้นหาขัั้นสูง</a>
        </div>

        <?php
        try {
            if (isset($_GET['selected'])) {
                if ($searchSelect == 'author') {
                    require "database.php";
                    $db_con = new Database();
                    $result = $db_con->selectThesisFull();
                    $countFound = 0;

                    $thesisIdFound = 0;
                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            $count = 1;
                            foreach ($row['author_member'] as $member) {
                                if ((strpos($member['prefix'], $dataInput) !== false) or (strpos($member['name'], $dataInput) !== false) or (strpos($member['lastname'], $dataInput) !== false)) {
                                    if ($thesisIdFound != $row['thesis_id']) {
                                        $thesisIdFound = $row['thesis_id'];
                                        //show list
                                        echo "<div class='border border-dark w-100 p-3 d-flex flex-column'>
                                            <a class='text-dark' id='thesisName' href='thesis?id=$row[thesis_id]'>
                                            <div class='fw-bold'>$row[thai_name]</div>
                                            <div class='fw-bold'>$row[english_name]</div>
                                            </a>
                                            <div>คณะผู้จัดทำ ";
                                        foreach ($row['author_member'] as $member) {
                                            $nameAuthor = $member['prefix'] . "" . $member['name'] . " " . $member['lastname'];
                                            echo "<div class='d-inline'>$nameAuthor</div>";
                                            echo count($row['author_member']);
                                            if ($count != count($row['author_member'])) {
                                                echo "<span class='text-dark'>, </span>";
                                                $count++;
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

                                        $keyword = explode(", ", $row['keyword']);
                                        echo "<div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp";
                                        for ($i = 0; $i < count($keyword); $i++) {
                                            echo "<a style='text-decoration:none;' href='search?keyword=$keyword[$i]'>$keyword[$i]</a>";
                                            if (!($i == count($keyword) - 1)) {
                                                echo ",&nbsp";
                                            }
                                        }
                                        echo "</div>";

                                        echo "<div>ปีที่พิมพ์เล่ม <a href='search?printed=$row[printed_year]' class='link-primary' style='text-decoration:none;'>$row[printed_year]</a></div>";
                                        echo "</div>";

                                        $countFound++;
                                    }
                                }
                            }
                        }
                        if ($countFound == 0) {
                            echo "<a href='#' class='list-group-item list-group-item-action text-truncate'>ไม่พบข้อมูล</a>";
                        }
                    }
                } else {
                    if ($searchSelect === 'all') {
                        $sql = "SELECT * 
                        FROM thesis_document 
                        WHERE thai_name LIKE :input
                        OR english_name LIKE :input
                        OR abstract LIKE :input
                        OR printed_year LIKE :input
                        OR approval_year LIKE :input
                        OR semester LIKE :input
                        OR keyword LIKe :input
                        OR prefix_chairman LIKE :input
                        OR name_chairman LIKE :input
                        OR surname_chairman LIKE :input
                        OR prefix_director1 LIKE :input
                        OR name_director1 LIKE :input
                        OR surname_director1 LIKe :input
                        OR prefix_director2 LIKE :input
                        OR name_director2 LIKE :input
                        OR surname_director2 LIKe :input
                        OR prefix_advisor LIKE :input
                        OR name_advisor LIKE :input
                        OR surname_advisor LIKE :input
                        OR prefix_coAdvisor LIKE :input
                        OR name_coAdvisor LIKE :input
                        OR surname_coAdvisor LIKE :input";
                        $likeInput = "%" . $dataInput . "%";
                        $insert_thesis = $conn->prepare($sql);
                        $insert_thesis->bindParam(":input", $likeInput);
                    } else if ($searchSelect === 'thesis_name') {
                        $sql = "SELECT * FROM thesis_document
                        WHERE thai_name LIKE :input
                        OR english_name LIKE :input";
                        $likeInput = "%" . $dataInput . "%";
                        $insert_thesis = $conn->prepare($sql);
                        $insert_thesis->bindParam(":input", $likeInput);
                    } else if ($searchSelect === 'keyword') {
                        $sql = "SELECT * FROM thesis_document
                        WHERE keyword LIKE :input";
                        $likeInput = "%" . $dataInput . "%";
                        $insert_thesis = $conn->prepare($sql);
                        $insert_thesis->bindParam(":input", $likeInput);
                    } else if ($searchSelect === 'printed_year') {
                        $sql = "SELECT * FROM thesis_document
                        WHERE printed_year LIKE :input";
                        $likeInput = "%" . $dataInput . "%";
                        $insert_thesis = $conn->prepare($sql);
                        $insert_thesis->bindParam(":input", $likeInput);
                    } else if ($searchSelect === 'semester') {
                        $semesterYear = explode("/", $dataInput);
                        if (count($semesterYear) == 2) {
                            $semester = $semesterYear[0];
                            $years = $semesterYear[1];

                            $sql = "SELECT * FROM thesis_document
                            WHERE semester LIKE :semester
                            AND approval_year LIKE :years";
                            $likeSemester = "%" . $semester . "%";
                            $likeYears = "%" . $years . "%";
                            $insert_thesis = $conn->prepare($sql);
                            $insert_thesis->bindParam(":semester", $likeSemester);
                            $insert_thesis->bindParam(":years", $likeYears);
                        } else {
                            $sql = "SELECT * FROM thesis_document
                            WHERE semester LIKE :input
                            OR approval_year LIKE :input";
                            $likeInput = "%" . $dataInput . "%";
                            $insert_thesis = $conn->prepare($sql);
                            $insert_thesis->bindParam(":input", $dataInput);
                        }
                    } else if ($searchSelect === 'abstract') {
                        $sql = "SELECT * FROM thesis_document
                            WHERE abstract LIKE :input";
                        $likeInput = "%" . $dataInput . "%";
                        $insert_thesis = $conn->prepare($sql);
                        $insert_thesis->bindParam(":input", $dataInput);
                    } else if ($searchSelect === 'advisor') {
                        if (strpos($dataInput, " ") !== false) {
                            $advisor = explode(" ", $dataInput);
                            switch (count($advisor)) {
                                case 2: {
                                        $sql = "SELECT * FROM thesis_document 
                                    WHERE name_advisor LIKE :input1 AND surname_advisor LIKE :input2";
                                        $likeInput1 = "%" . $advisor[0] . "%";
                                        $likeInput2 = "%" . $advisor[1] . "%";
                                        $insert_thesis = $conn->prepare($sql);
                                        $insert_thesis->bindParam(":input1", $likeInput1);
                                        $insert_thesis->bindParam(":input2", $likeInput2);
                                    }
                                    break;
                                case 3: {
                                        $sql = "SELECT * FROM thesis_document 
                                    WHERE prefix_advisor LIKE :input1 AND name_advisor LIKE :input2 AND surname_advisor LIKE :input3";
                                        $likeInput1 = "%" . $advisor[0] . "%";
                                        $likeInput2 = "%" . $advisor[1] . "%";
                                        $likeInput3 = "%" . $advisor[2] . "%";
                                        $insert_thesis = $conn->prepare($sql);
                                        $insert_thesis->bindParam(":input1", $likeInput1);
                                        $insert_thesis->bindParam(":input2", $likeInput2);
                                        $insert_thesis->bindParam(":input3", $likeInput3);
                                    }
                                    break;
                            }
                        } else {
                            $sql = "SELECT * FROM thesis_document 
                        WHERE prefix_advisor LIKE :input OR name_advisor LIKE :input OR surname_advisor LIKE :input
                                OR prefix_coAdvisor LIKE :input OR name_coAdvisor LIKE :input OR surname_coAdvisor";
                            $likeInput = "%" . $dataInput . "%";
                            $insert_thesis = $conn->prepare($sql);
                            $insert_thesis->bindParam(":input", $likeInput);
                        }
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

                            // echo "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>$row[keyword]</a></div>";

                            $keyword = explode(", ", $row['keyword']);
                            echo "<div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp";
                            for ($i = 0; $i < count($keyword); $i++) {
                                echo "<a style='text-decoration:none;' href='search?keyword=$keyword[$i]'>$keyword[$i]</a>";
                                if (!($i == count($keyword) - 1)) {
                                    echo ",&nbsp";
                                }
                            }
                            echo "</div>";

                            echo "<div>ปีที่พิมพ์เล่ม <a href='search?printed=$row[printed_year]' class='link-primary' style='text-decoration:none;'>$row[printed_year]</a></div>";
                            echo "</div>";
                        }
                    } else {
                        echo "ไม่พบข้อมูล";
                    }
                }
            } else {
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
                    $insert_thesis->bindParam(":semester", $semester);
                    $insert_thesis->bindParam(":approval_year", $approval_year);
                } else if ($searchSelect === "keyword") {
                    $sql = "SELECT * FROM thesis_document
                    WHERE keyword LIKE :keyword";
                    $keywordLike = "%" . $keyword . "%";
                    $insert_thesis = $conn->prepare($sql);
                    $insert_thesis->bindParam(":keyword", $keywordLike);
                } else if ($searchSelect === "abstract") {
                    $sql = "SELECT * FROM thesis_document
                    WHERE abstract LIKE :input";
                    $abstractLike = "%" . $abstract . "%";
                    $insert_thesis = $conn->prepare($sql);
                    $insert_thesis->bindParam(":input", $abstractLike);
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

                        // echo "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>$row[keyword]</a></div>";

                        $keyword = explode(", ", $row['keyword']);
                        echo "<div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp";
                        for ($i = 0; $i < count($keyword); $i++) {
                            echo "<a style='text-decoration:none;' href='search?keyword=$keyword[$i]'>$keyword[$i]</a>";
                            if (!($i == count($keyword) - 1)) {
                                echo ",&nbsp";
                            }
                        }
                        echo "</div>";

                        echo "<div>ปีที่พิมพ์เล่ม <a href='search?printed=$row[printed_year]' class='link-primary' style='text-decoration:none;'>$row[printed_year]</a></div>";
                        echo "</div>";
                    }
                } else {
                    echo "ไม่พบข้อมูล";
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        ?>
    </div>

    <script>
        function submitSearch() {
            let selectSearch = document.getElementById('selectSearch').value;
            let inputSearch = document.getElementById('inputSearch');
        }

        inputSearch.addEventListener('keyup', () => {
            let input = inputSearch.value;
            let searchingDOM = document.getElementById('searching');

            if (input != '') {
                searchingDOM.classList.remove('d-none');

                let options = {
                    method: 'GET',
                    input: input,
                }
                let url = '/FinalProj/searchbar_db?data=' + input + "&selected=" + selectSearch.value;
                fetch(url, options)
                    .then(response => {
                        return response.text()
                    })
                    .then(data => searchingDOM.innerHTML = data)
            } else {
                searchingDOM.classList.add('d-none');
                searchingDOM.innerHTML = "";
            }
        });
    </script>


    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>