<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RMUTT</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php
    require './template/header.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }

    require 'dbconnect.php';
    $sql = $conn->prepare("SELECT * FROM thesis_document LEFT JOIN author_thesis ON thesis_document.thesis_id = author_thesis.thesis_id 
    WHERE thesis_document.thesis_id = :id");
    $sql->bindParam(":id", $id);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_OBJ);
    $thesis = [];
    $i = 1;

    foreach ($result as $row) {
        $thesis['thesis_id'] = $row->thesis_id;
        $thesis['thai_name'] = $row->thai_name;
        $thesis['english_name'] = $row->english_name;
        $thesis['abstract'] = $row->abstract;
        $thesis['printed_year'] = $row->printed_year;
        $thesis['semester'] = $row->semester;
        $thesis['approval_year'] = $row->approval_year;
        $thesis['thesis_file'] = $row->thesis_file;
        $thesis['approval_file'] = $row->approval_file;
        $thesis['poster_file'] = $row->poster_file;
        $thesis['keyword'] = $row->keyword;
        $thesis['prefix_chairman'] = $row->prefix_chairman;
        $thesis['name_chairman'] = $row->name_chairman;
        $thesis['surname_chairman'] = $row->surname_chairman;
        $thesis['prefix_director1'] = $row->prefix_director1;
        $thesis['name_director1'] = $row->name_director1;
        $thesis['surname_director1'] = $row->surname_director1;
        $thesis['prefix_director2'] = $row->prefix_director2;
        $thesis['name_director2'] = $row->name_director2;
        $thesis['surname_director2'] = $row->surname_director2;
        $thesis['prefix_advisor'] = $row->prefix_advisor;
        $thesis['name_advisor'] = $row->name_advisor;
        $thesis['surname_advisor'] = $row->surname_advisor;
        $thesis['prefix_coAdvisor'] = $row->prefix_coAdvisor;
        $thesis['name_coAdvisor'] = $row->name_coAdvisor;
        $thesis['surname_coAdvisor'] = $row->surname_coAdvisor;

        $thesis['author_member_id']["member$i"] = $row->student_id;
        $thesis['author_member_prefix']["member$i"] = $row->prefix;
        $thesis['author_member_name']["member$i"] = $row->name;
        $thesis['author_member_surname']["member$i"] = $row->lastname;

        $i++;
    }

    echo "<div class='container w-75 my-5 d-flex flex-column gap-3'>
            <h1> $thesis[thai_name] <br> $thesis[english_name] </h1>
            <div class='row'>
                <div class='fw-bold col-lg-2 col-md-16'>คณะผู้จัดทำ</div>
                <div class='col-auto d-flex flex-column'>";
    for ($i = 1; $i <= count($thesis['author_member_id']); $i++) {
        echo $thesis['author_member_id']["member$i"] . " ";
        echo $thesis['author_member_prefix']["member$i"] . "";
        echo $thesis['author_member_name']["member$i"] .  "&nbsp;";
        echo $thesis['author_member_surname']["member$i"];

        
    }
    echo "      </div>
            </div>";

    echo "<div class='row'>
        <div class='fw-bold col-lg-2 col-md-16'>อาจารย์ที่ปรึกษา</div>
        <div class='col-auto d-flex flex-column'>
            <a href='search?advisor=$thesis[prefix_advisor]_$thesis[name_advisor]_$thesis[surname_advisor]'>
            $thesis[prefix_advisor]$thesis[name_advisor] $thesis[surname_advisor] </a>
        </div>
        ";
        if($thesis['prefix_coAdvisor'] != '') {
            echo ",&nbsp";
            echo "<div class='col-auto d-flex flex-column'>";
            echo "<a href='search?coAdvisor=$thesis[prefix_coAdvisor]_$thesis[name_coAdvisor]_$thesis[surname_coAdvisor]'>$thesis[prefix_coAdvisor]$thesis[name_coAdvisor] $thesis[surname_coAdvisor]</a>";
            echo "</div>";
        }

    echo "
    </div>";

    echo "<div class='row'>
        <div class='fw-bold col-md-16 col-lg-2'>ปีที่พิมพ์เล่ม</div>
        <a href='search?printed=$thesis[printed_year]' class='col-md-16 col-lg-auto'>$thesis[printed_year]</a>
        </div>";
        

    echo "<div class='row'>
            <div class='fw-bold col-md-16 col-lg-2'>ปีที่อนุมัติเล่ม</div>
            <a href='search?approval=$thesis[semester]/$thesis[approval_year]' class='link-primary col-md-16 col-lg-auto'>$thesis[semester]/$thesis[approval_year]</a>
        </div>";

    echo "<div class='row'>
            <div class='fw-bold col-md-16 col-lg-2'>คำสำคัญ</div>
            <div class='col-md-16 col-lg-auto'>$thesis[keyword]</div>
        </div>";

    echo "<div>
            <span class='fw-bold'>บทคัดย่อ</span><br>
            <p style='word-wrap: break-word;'>$thesis[abstract]</p>
        </div>";

    echo "<div class='container-fluid d-flex gap-3 justify-content-center'>
                <a class='btn btn-warning' href='thesis_update?id=$id'>แก้ไข</a>
                <a class='btn btn-danger' href='thesis_delete?id=$id' onclick=\"return confirm('ต้องการลบข้อมูลหรือไม่')\">ลบ</a>
        </div>";


    ?>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>