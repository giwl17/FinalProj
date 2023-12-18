<?php
include "dbconnect.php";
if (isset($_GET['data']) && $_GET['selected']) {
    $input = $_GET['data'];
    $selected = $_GET['selected'];


    if ($selected == 'all') {
        $inputLike = "" . $input . "%";
        require "dbconnect.php";
        $stmt = $conn->prepare("SELECT * FROM thesis_document
        INNER JOIN author_thesis ON  thesis_document.thesis_id = author_thesis.thesis_id
        WHERE thai_name LIKE :input 
        OR english_name LIKE :input
        OR abstract LIKE :input
        OR keyword LIKE :input
        OR printed_year LIKE :input
        OR prefix_advisor LIKE :input
        OR name_advisor LIKE :input
        OR surname_advisor LIKE :input
        OR prefix_coAdvisor LIKE :input
        OR name_coAdvisor LIKE :input
        OR surname_coAdvisor LIKE :input
        OR prefix LIKE :input
        OR name LIKE :input
        OR lastname LIKE :input
        LIMIT 10");
        $stmt->execute([':input' => $inputLike]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            $printed = "";
            $keyword = "";
            $author_name = "";
            $abstract = "";
            $thaiName = "";
            $englishName = "";

            $isShow_advisor = false;
            $isShow_coAdvisor = false;

            echo "<div class='list-group w-100'>";
            foreach ($result as $row) {
                if (strpos($row['thai_name'], $input) === 0) {
                    if ($row['thai_name'] !== $thaiName) {
                        echo "<a href='thesis?id=$row[thesis_id]' class='list-group-item list-group-item-action'>ชื่อปริญญานิพนธ์ : $row[thai_name]</a>";
                        $thaiName = $row['thai_name'];
                    }
                } else if (strpos($row['english_name'], $input) === 0) {
                    if ($row['english_name'] !== $englishName) {
                        echo "<a href='thesis?id=$row[thesis_id]' class='list-group-item list-group-item-action'>ชื่อปริญญานิพนธ์ : $row[english_name]</a>";
                        $englishName = $row['english_name'];
                    }
                } else if (strpos($row['abstract'], $input) === 0) {
                    if ($row['abstract'] !== $abstract) {
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>บทคัดย่อ : $row[abstract]</a>";
                        $abstract = $row['abstract'];
                    }
                } else if (strpos($row['printed_year'], $input) === 0) {
                    if ($row['printed_year'] !== $printed) {
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>ปีที่ตีพิมพ์เล่ม : $row[printed_year]</a>";
                        $printed = $row['printed_year'];
                    }
                } else if (strpos($row['keyword'], $input) === 0) {
                    if ($row['keyword'] != $keyword) {
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>คำสำคัญ : $row[keyword]</a>";
                        $keyword = $row['keyword'];
                    }
                } else if (strpos($row['prefix_advisor'], $input) === 0) {
                    if (!$isShow_advisor) {
                        $advisor_name = $row['prefix_advisor'] . $row['name_advisor'] . " " . $row['surname_advisor'];
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>อาจารย์ที่ปรึกษาหลัก : $advisor_name </a>";
                        $isShow_advisor = true;
                    }
                } else if (strpos($row['name_advisor'], $input) === 0) {
                    if (!$isShow_advisor) {
                        $advisor_name = $row['prefix_advisor'] . $row['name_advisor'] . " " . $row['surname_advisor'];
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>อาจารย์ที่ปรึกษาหลัก : $advisor_name </a>";
                        $isShow_advisor = true;
                    }
                } else if (strpos($row['surname_advisor'], $input) === 0) {
                    if (!$isShow_advisor) {
                        $advisor_name = $row['prefix_advisor'] . $row['name_advisor'] . " " . $row['surname_advisor'];
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>อาจารย์ที่ปรึกษาหลัก : $advisor_name </a>";
                        $isShow_advisor = true;
                    }
                } else if (strpos($row['prefix_coAdvisor'], $input) === 0) {
                    if (!$isShow_coAdvisor) {
                        $coAdvisor_name = $row['prefix_coAdvisor'] . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'];
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>อาจารย์ที่ปรึกษาร่วม : $coAdvisor_name </a>";
                        $isShow_coAdvisor = true;
                    }
                } else if (strpos($row['name_coAdvisor'], $input) === 0) {
                    if (!$isShow_coAdvisor) {
                        $coAdvisor_name = $row['prefix_coAdvisor'] . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'];
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>อาจารย์ที่ปรึกษาร่วม : $coAdvisor_name </a>";
                        $isShow_coAdvisor = true;
                    }
                } else if (strpos($row['surname_coAdvisor'], $input) === 0) {
                    if (!$isShow_coAdvisor) {
                        $coAdvisor_name = $row['prefix_coAdvisor'] . $row['name_coAdvisor'] . " " . $row['surname_coAdvisor'];
                        echo "<a href='?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>อาจารย์ที่ปรึกษาร่วม : $coAdvisor_name </a>";
                        $isShow_coAdvisor = true;
                    }
                }
                if (strpos($row['student_id'], $input) === 0) {
                    if ($row['prefix'] . $row['name'] . " " . $row['lastname'] !== $author_name) {
                        $author_name = $row['prefix'] . $row['name'] . " " . $row['lastname'];
                        echo "<a href='thesis?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>รหัสนักศึกษา : $row[student_id] </a>";
                    }
                } else if (strpos($row['prefix'], $input) === 0) {
                    if ($row['prefix'] . $row['name'] . " " . $row['lastname'] !== $author_name) {
                        $author_name = $row['prefix'] . $row['name'] . " " . $row['lastname'];
                        echo "<a href='thesis?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>นักศึกษา : $author_name </a>";
                    }
                } else if (strpos($row['name'], $input) === 0) {
                    if ($row['prefix'] . $row['name'] . " " . $row['lastname'] !== $author_name) {
                        $author_name = $row['prefix'] . $row['name'] . " " . $row['lastname'];
                        echo "<a href='thesis?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>นักศึกษา : $author_name </a>";
                    }
                } else if (strpos($row['lastname'], $input) === 0) {
                    if ($row['prefix'] . $row['name'] . " " . $row['lastname'] !== $author_name) {
                        $author_name = $row['prefix'] . $row['name'] . " " . $row['lastname'];
                        echo "<a href='thesis?id=$row[thesis_id]' class='list-group-item list-group-item-action text-truncate'>นักศึกษา : $author_name </a>";
                    }
                }
            }
            echo "</div>";
        } else {
            echo "<div class='list-group'><a class='list-group-item'>ไม่พบข้อมูล</a></div>";
        }
    }
}
// echo "hello";
