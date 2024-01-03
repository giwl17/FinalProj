<?php
require "database.php";
$conn = new Database();
$showNoData = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = '';
    if (isset($_POST['inputSearch1'])) {
        $inputSearch1 = $_POST['inputSearch1'];
        $selectSearch1 = $_POST['selectSearch1'];
    }
    if (isset($_POST['inputSearch2'])) {
        $inputSearch2 = $_POST['inputSearch2'];
        $selectSearch2 = $_POST['selectSearch2'];
    }
    if (isset($_POST['inputSearch3'])) {
        $inputSearch3 = $_POST['inputSearch3'];
        $selectSearch3 = $_POST['selectSearch3'];
    }
    if (isset($_POST['inputSearch4'])) {
        $inputSearch4 = $_POST['inputSearch4'];
        $selectSearch4 = $_POST['selectSearch4'];
    }
    if (isset($_POST['inputSearch5'])) {
        $inputSearch5 = $_POST['inputSearch5'];
        $selectSearch5 = $_POST['selectSearch5'];
    }

    if (isset($_POST['selectCondition1'])) {
        $selectCondition1 = $_POST['selectCondition1'];
    }

    if (isset($_POST['selectCondition2'])) {
        $selectCondition2 = $_POST['selectCondition2'];
    }

    if (isset($_POST['selectCondition3'])) {
        $selectCondition3 = $_POST['selectCondition3'];
    }

    if (isset($_POST['selectCondition4'])) {
        $selectCondition4 = $_POST['selectCondition4'];
    }

    if ($inputSearch1 !== '') {
        $like1 = "'%" . $inputSearch1 . "%'";
        if ($selectSearch1 === 'all') {
            $selectSearch1 = "ทั้งหมด";
            $sql = "SELECT * FROM thesis_document 
            WHERE (thai_name LIKE $like1
            OR english_name LIKE $like1
            OR abstract LIKE $like1
            OR printed_year LIKE $like1
            OR semester LIKE $like1
            OR approval_year LIKe $like1
            OR keyword LIKE $like1
            OR prefix_advisor LIKe $like1
            OR name_advisor LIKE $like1
            OR surname_advisor LIKE $like1
            OR prefix_coAdvisor LIKe $like1
            OR name_coAdvisor LIKE $like1
            OR surname_coAdvisor LIKE $like1
            )";
        } else if ($selectSearch1 === 'thesis_name') {
            $selectSearch1 = "ชื่อปริญญานิพนธ์";
            $sql = "SELECT * FROM thesis_document WHERE (thai_name LIKE $like1 OR english_name LIKE $like1)";
        } else if ($selectSearch1 === 'keyword') {
            $selectSearch1 = "คำสำคัญ";
            $sql = "SELECT * FROM thesis_document WHERE (keyword LIKE $like1)";
        } else if ($selectSearch1 === 'printed_year') {
            $selectSearch1 = "ปีตีพิมพ์เล่ม";
            $sql = "SELECT * FROM thesis_document WHERE (printed_year LIKE $like1)";
        } else if ($selectSearch1 === 'semester') {
            $selectSearch1 = "ภาคการศึกษา/ปี ที่อนุมัติเล่ม";
            $sql = "SELECT * FROM thesis_document WHERE (semester LIKE $like1)";
        } else if ($selectSearch1 === 'abstract') {
            $selectSearch1 = "บทคัดย่อ";
            $sql = "SELECT * FROM thesis_document WHERE (abstract LIKE $like1)";
        } else if ($selectSearch1 === 'author') {
            $selectSearch1 = "ชื่อหรือนามสกุลคณะผู้จัดทำ";
            $sql = "SELECT * FROM thesis_document";
        } else if ($selectSearch1 === 'advisor') {
            $selectSearch1 = "ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา";
            $sql = "SELECT * FROM thesis_document WHERE (prefix_advisor LIKE $like1 OR name_advisor LIKE $like1 OR surname_advisor LIKE $like1
            OR prefix_coAdvisor LIKE $like1 OR name_coAdvisor LIKE $like1 OR surname_coAdvisor LIKE $like1)";
        }
        if ($inputSearch2 !== '') {
            $like2 = "'%" . $inputSearch2 . "%'";
            if ($selectSearch2 === 'all') {
                $selectSearch2 = "ทั้งหมด";
                $sql .= " " . $selectCondition1 . " (thai_name LIKE $like2
                OR english_name LIKE $like2
                OR abstract LIKE $like2
                OR printed_year LIKE $like2
                OR semester LIKE $like2
                OR approval_year LIKe $like2
                OR keyword LIKE $like2
                OR prefix_advisor LIKe $like2
                OR name_advisor LIKE $like2
                OR surname_advisor LIKE $like2
                OR prefix_coAdvisor LIKe $like2
                OR name_coAdvisor LIKE $like2
                OR surname_coAdvisor LIKE $like2)";
            } else if ($selectSearch2 === 'thesis_name') {
                $selectSearch2 = "ชื่อปริญญานิพนธ์";
                $sql .= " " . $selectCondition1 . " (thai_name LIKE $like2 OR english_name LIKE $like2)";
                // echo $sql;
            } else if ($selectSearch2 === 'keyword') {
                $selectSearch2 = "คำสำคัญ";
                $sql .= " " . $selectCondition1 . " (keyword LIKE $like2)";
            } else if ($selectSearch2 === 'printed_year') {
                $selectSearch2 = "ปีตีพิมพ์เล่ม";
                $sql .= " " . $selectCondition1 . " (printed_year LIKE $like2)";
            } else if ($selectSearch2 === 'semester') {
                $selectSearch2 = "ภาคการศึกษา/ปี ที่อนุมัติเล่ม";
                $sql .= " " . $selectCondition1 . " (semester LIKE $like2)";
            } else if ($selectSearch2 === 'abstract') {
                $selectSearch2 = "บทคัดย่อ";
                $sql .= " " . $selectCondition1 . " (abstract LIKE $like2)";
            } else if ($selectSearch2 === 'author') {
                $selectSearch2 = "ชื่อหรือนามสกุลคณะผู้จัดทำ";
            } else if ($selectSearch2 === 'advisor') {
                $selectSearch2 = "ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา";
                $sql .= " " . $selectCondition1 . " (prefix_advisor LIKE $like2 OR name_advisor LIKE $like2 OR surname_advisor LIKE $like2
            OR prefix_coAdvisor LIKE $like2 OR name_coAdvisor LIKE $like2 OR surname_coAdvisor LIKE $like2)";
            }

            if ($inputSearch3 !== '') {
                $like3 = "'%" . $inputSearch3 . "%'";
                if ($selectSearch3 === 'all') {
                    $selectSearch3 = "ทั้งหมด";
                    $sql .= " " . $selectCondition2 . " (thai_name LIKE $like3
                    OR english_name LIKE $like3
                    OR abstract LIKE $like3
                    OR printed_year LIKE $like3
                    OR semester LIKE $like3
                    OR approval_year LIKe $like3
                    OR keyword LIKE $like3
                    OR prefix_advisor LIKe $like3
                    OR name_advisor LIKE $like3
                    OR surname_advisor LIKE $like3
                    OR prefix_coAdvisor LIKe $like3
                    OR name_coAdvisor LIKE $like3
                    OR surname_coAdvisor LIKE $like3)";
                } else if ($selectSearch3 === 'thesis_name') {
                    $selectSearch3 = "ชื่อปริญญานิพนธ์";
                    $sql .= " " . $selectCondition2 . " (thai_name LIKE $like3 OR english_name LIKE $like3)";
                    // echo $sql;
                } else if ($selectSearch3 === 'keyword') {
                    $selectSearch3 = "คำสำคัญ";
                    $sql .= " " . $selectCondition2 . " (keyword LIKE $like3)";
                } else if ($selectSearch3 === 'printed_year') {
                    $selectSearch3 = "ปีตีพิมพ์เล่ม";
                    $sql .= " " . $selectCondition2 . " (printed_year LIKE $like3)";
                } else if ($selectSearch3 === 'semester') {
                    $selectSearch3 = "ภาคการศึกษา/ปี ที่อนุมัติเล่ม";
                    $sql .= " " . $selectCondition2 . " (semester LIKE $like3)";
                } else if ($selectSearch3 === 'abstract') {
                    $selectSearch3 = "บทคัดย่อ";
                    $sql .= " " . $selectCondition2 . " (abstract LIKE $like3)";
                } else if ($selectSearch3 === 'author') {
                    $selectSearch3 = "ชื่อหรือนามสกุลคณะผู้จัดทำ";
                } else if ($selectSearch3 === 'advisor') {
                    $selectSearch3 = "ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา";
                    $sql .= " " . $selectCondition2 . " (prefix_advisor LIKE $like3 OR name_advisor LIKE $like3 OR surname_advisor LIKE $like3
            OR prefix_coAdvisor LIKE $like3 OR name_coAdvisor LIKE $like3 OR surname_coAdvisor LIKE $like3)";
                }
            }

            if ($inputSearch4 !== '') {
                $like4 = "'%" . $inputSearch4 . "%'";
                if ($selectSearch4 === 'all') {
                    $selectSearch4 = "ทั้งหมด";
                    $sql .= " " . $selectCondition3 . " (thai_name LIKE $like4
                    OR english_name LIKE $like4
                    OR abstract LIKE $like4
                    OR printed_year LIKE $like4
                    OR semester LIKE $like4
                    OR approval_year LIKe $like4
                    OR keyword LIKE $like4
                    OR prefix_advisor LIKe $like4
                    OR name_advisor LIKE $like4
                    OR surname_advisor LIKE $like4
                    OR prefix_coAdvisor LIKe $like4
                    OR name_coAdvisor LIKE $like4
                    OR surname_coAdvisor LIKE $like4)";
                } else if ($selectSearch4 === 'thesis_name') {
                    $selectSearch4 = "ชื่อปริญญานิพนธ์";
                    $sql .= " " . $selectCondition3 . " (thai_name LIKE $like4 OR english_name LIKE $like4)";
                    echo $sql;
                } else if ($selectSearch4 === 'keyword') {
                    $selectSearch4 = "คำสำคัญ";
                    $sql .= " " . $selectCondition3 . " (keyword LIKE $like4)";
                } else if ($selectSearch4 === 'printed_year') {
                    $selectSearch4 = "ปีตีพิมพ์เล่ม";
                    $sql .= " " . $selectCondition3 . " (printed_year LIKE $like4)";
                } else if ($selectSearch4 === 'semester') {
                    $selectSearch4 = "ภาคการศึกษา/ปี ที่อนุมัติเล่ม";
                    $sql .= " " . $selectCondition3 . " (semester LIKE $like4)";
                } else if ($selectSearch4 === 'abstract') {
                    $selectSearch4 = "บทคัดย่อ";
                    $sql .= " " . $selectCondition3 . " (abstract LIKE $like4)";
                } else if ($selectSearch4 === 'author') {
                    $selectSearch4 = "ชื่อหรือนามสกุลคณะผู้จัดทำ";
                } else if ($selectSearch4 === 'advisor') {
                    $selectSearch4 = "ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา";
                    $sql .= " " . $selectCondition3 . " (prefix_advisor LIKE $like4 OR name_advisor LIKE $like4 OR surname_advisor LIKE $like4
            OR prefix_coAdvisor LIKE $like4 OR name_coAdvisor LIKE $like4 OR surname_coAdvisor LIKE $like4)";
                }

                if ($inputSearch5 !== '') {
                    $like5 = "'%" . $inputSearch5 . "%'";
                    if ($selectSearch5 === 'all') {
                        $selectSearch5 = "ทั้งหมด";
                        $sql .= " " . $selectCondition4 . " (thai_name LIKE $like5
                        OR english_name LIKE $like5
                        OR abstract LIKE $like5
                        OR printed_year LIKE $like5
                        OR semester LIKE $like5
                        OR approval_year LIKe $like5
                        OR keyword LIKE $like5
                        OR prefix_advisor LIKe $like5
                        OR name_advisor LIKE $like5
                        OR surname_advisor LIKE $like5
                        OR prefix_coAdvisor LIKe $like5
                        OR name_coAdvisor LIKE $like5
                        OR surname_coAdvisor LIKE $like5)";
                    } else if ($selectSearch5 === 'thesis_name') {
                        $selectSearch5 = "ชื่อปริญญานิพนธ์";
                        $sql .= " " . $selectCondition4 . " (thai_name LIKE $like5 OR english_name LIKE $like5)";
                        echo $sql;
                    } else if ($selectSearch5 === 'keyword') {
                        $selectSearch5 = "คำสำคัญ";
                        $sql .= " " . $selectCondition4 . " (keyword LIKE $like5)";
                    } else if ($selectSearch5 === 'printed_year') {
                        $selectSearch5 = "ปีตีพิมพ์เล่ม";
                        $sql .= " " . $selectCondition4 . " (printed_year LIKE $like5)";
                    } else if ($selectSearch5 === 'semester') {
                        $selectSearch5 = "ภาคการศึกษา/ปี ที่อนุมัติเล่ม";
                        $sql .= " " . $selectCondition4 . " (semester LIKE $like5)";
                    } else if ($selectSearch5 === 'abstract') {
                        $selectSearch5 = "บทคัดย่อ";
                        $sql .= " " . $selectCondition4 . " (abstract LIKE $like5)";
                    } else if ($selectSearch5 === 'author') {
                        $selectSearch5 = "ชื่อหรือนามสกุลคณะผู้จัดทำ";
                    } else if ($selectSearch5 === 'advisor') {
                        $selectSearch5 = "ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา";
                        $sql .= " " . $selectCondition4 . " (prefix_advisor LIKE $like5 OR name_advisor LIKE $like5 OR surname_advisor LIKE $like5
                OR prefix_coAdvisor LIKE $like5 OR name_coAdvisor LIKE $like5 OR surname_coAdvisor LIKE $like5)";
                    }
                }
            }
        }
    }
}

if ($sql === '') {
    $rows = $conn->selectThesisFull();
} else {
    $rows = $conn->selectThesisFullSQL($sql);
}
// print_r($rows);
// echo count($rows);

if (count($rows) == 0) {
    echo "<div class='text-center'>ไม่พบข้อมูลที่ค้นหา</div>";
} else {
    $searchTxt = "";
    if ($sql !== '') {
        $searchTxt .= "<div class='bg-light p-3 border border-dark'>ค้นหา: \"" . $inputSearch1 . "\" จาก " . $selectSearch1;
        if ($inputSearch2 !== '') {
            $searchTxt .= " <span class='fw-bold'> $selectCondition1</span> \"$inputSearch2\" จาก $selectSearch2";
            if ($inputSearch3 !== '') {
                $searchTxt .= " <span class='fw-bold'> $selectCondition2</span> \"$inputSearch3\" จาก $selectSearch3";
                if ($inputSearch4 !== '') {
                    $searchTxt .= " <span class='fw-bold'> $selectCondition3</span> \"$inputSearch4\" จาก $selectSearch4";
                    if ($inputSearch5 !== '') {
                        $searchTxt .= " <span class='fw-bold'> $selectCondition4</span> \"$inputSearch5\" จาก $selectSearch5";
                    }
                }
            }
        }
        $searchTxt .= "</div>";
        echo $searchTxt;
    }
    if ($selectSearch1 !== 'ชื่อหรือนามสกุลคณะผู้จัดทำ' OR $selectSearch2 !== 'ชื่อหรือนามสกุลคณะผู้จัดทำ' OR $selectSearch3 !== 'ชื่อหรือนามสกุลคณะผู้จัดทำ' OR $selectSearch4 !== 'ชื่อหรือนามสกุลคณะผู้จัดทำ' OR $selectSearch25!== 'ชื่อหรือนามสกุลคณะผู้จัดทำ') {
        echo "<div>พบรายการทั้งหมด "  . count($rows) . " รายการ</div>";
    }
    for ($i = 0; $i < count($rows); $i++) {
        if ($selectSearch1 === 'ชื่อหรือนามสกุลคณะผู้จัดทำ' OR $selectSearch2 === 'ชื่อหรือนามสกุลคณะผู้จัดทำ') {
            foreach ($rows[$i]['author_member'] as $key => $value) {
                if ((strpos($rows[$i]['author_member'][$key]['prefix'], $inputSearch1) !== false || strpos($rows[$i]['author_member'][$key]['name'], $inputSearch1) !== false)) {
                    echo "<div class='border border-dark w-100 p-3 d-flex flex-column'>";
                    echo "<a class='text-dark' href='../thesis?id=" . $rows[$i]['thesis_id'] . "'>";
                    echo "<div class='fw-bold'>" . $rows[$i]['thai_name'] . "</div>";
                    echo "<div class='fw-bold'>" . $rows[$i]['english_name'] . "</div>";
                    echo "</a>";
                    echo "<div>คณะผู้จัดทำ ";
                    $count = 0;
                    foreach ($rows[$i]['author_member'] as $key => $value) {
                        $name = $rows[$i]['author_member'][$key]['prefix'] . $rows[$i]['author_member'][$key]['name'] . " " . $rows[$i]['author_member'][$key]['lastname'];
                        echo "<div class='d-inline'>$name</div>";
                        if ($count < count($rows[$i]['author_member']) - 1) {
                            echo "<span class='text-dark'>, </span>";
                        }
                        $count++;
                    }
                    echo "</div>";
                    $u = '_';
                    echo "<div>อาจารยที่ปรึกษา <a href='../search?advisor=" . $rows[$i]['prefix_advisor'] . $u . $rows[$i]['name_advisor'] . $u . $rows[$i]['surname_advisor'] . "' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['prefix_advisor'] . $rows[$i]['name_advisor'] . " " . $rows[$i]['surname_advisor'] . "</a>";
                    if ($rows[$i]['prefix_coAdvisor'] != '') {
                        echo  ", ";
                        echo "<a href='../search?coAdvisor=" . $rows[$i]['prefix_coAdvisor'] . $u . $rows[$i]['name_coAdvisor'] . $u . $rows[$i]['surname_coAdvisor'] . "' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['prefix_coAdvisor'] . $rows[$i]['name_coAdvisor'] . " " . $rows[$i]['surname_coAdvisor'] . "</a>";
                    }
                    echo "</div>";

                    echo "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['keyword'] . "</a></div>";
                    echo "<div>ปีที่พิมพ์เล่ม <a href='../search?printed=" . $rows[$i]['printed_year'] . "' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['printed_year'] . "</a></div>";

                    echo "</div>";
                    $showNoData = true;
                    break;
                } else {
                    if (!$showNoData) {
                        echo "<div class='text-center'>ไม่พบข้อมูลที่ค้นหา</div>";
                        $showNoData = true;
                    }
                }
            }
        } else {
            echo "<div class='border border-dark w-100 p-3 d-flex flex-column'>";
            echo "<a class='text-dark' href='../thesis?id=" . $rows[$i]['thesis_id'] . "'>";
            echo "<div class='fw-bold'>" . $rows[$i]['thai_name'] . "</div>";
            echo "<div class='fw-bold'>" . $rows[$i]['english_name'] . "</div>";
            echo "</a>";
            echo "<div>คณะผู้จัดทำ ";
            $count = 0;
            foreach ($rows[$i]['author_member'] as $key => $value) {
                $name = $rows[$i]['author_member'][$key]['prefix'] . $rows[$i]['author_member'][$key]['name'] . " " . $rows[$i]['author_member'][$key]['lastname'];
                echo "<div class='d-inline'>$name</div>";
                if ($count < count($rows[$i]['author_member']) - 1) {
                    echo "<span class='text-dark'>, </span>";
                }
                $count++;
            }
            echo "</div>";
            $u = '_';
            echo "<div>อาจารยที่ปรึกษา <a href='../search?advisor=" . $rows[$i]['prefix_advisor'] . $u . $rows[$i]['name_advisor'] . $u . $rows[$i]['surname_advisor'] . "' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['prefix_advisor'] . $rows[$i]['name_advisor'] . " " . $rows[$i]['surname_advisor'] . "</a>";
            if ($rows[$i]['prefix_coAdvisor'] != '') {
                echo  ", ";
                echo "<a href='../search?coAdvisor=" . $rows[$i]['prefix_coAdvisor'] . $u . $rows[$i]['name_coAdvisor'] . $u . $rows[$i]['surname_coAdvisor'] . "' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['prefix_coAdvisor'] . $rows[$i]['name_coAdvisor'] . " " . $rows[$i]['surname_coAdvisor'] . "</a>";
            }
            echo "</div>";

            echo "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['keyword'] . "</a></div>";
            echo "<div>ปีที่พิมพ์เล่ม <a href='../search?printed=" . $rows[$i]['printed_year'] . "' class='link-primary' style='text-decoration:none;'>" . $rows[$i]['printed_year'] . "</a></div>";
            echo "</div>";
        }
    }
}
