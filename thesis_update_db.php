<?php
require_once 'dbconnect.php';
$id = $_POST['id'];

$thesis_name_th = $_POST['thesis_name_th'];
$thesis_name_en = $_POST['thesis_name_en'];
$abstract = $_POST['abstract'];

if (isset($_POST['member1'])) {
    $member1_id = $_POST['member1_id'];
    $member1_prefix = $_POST['member1_prefix'];
    $member1_firstname = $_POST['member1_firstname'];
    $member1_lastname = $_POST['member1_lastname'];
}

if (isset($_POST['member2'])) {
    $member2_id = $_POST['member2_id'];
    $member2_prefix = $_POST['member2_prefix'];
    $member2_firstname = $_POST['member2_firstname'];
    $member2_lastname = $_POST['member2_lastname'];
}
if (isset($_POST['member3'])) {
    $member3_id = $_POST['member3_id'];
    $member3_prefix = $_POST['member3_prefix'];
    $member3_firstname = $_POST['member3_firstname'];
    $member3_lastname = $_POST['member3_lastname'];
}

$advisor = $_POST['advisor'];
if ($advisor === 'other') {
    $advisor_prefix = $_POST['advisor_other_prefix'];
    $advisor_firstname = $_POST['advisor_other_firstname'];
    $advisor_lastname = $_POST['advisor_other_lastname'];
} else {
    $advisor_array = explode(' ', $advisor);
    $advisor_prefix = $advisor_array[0];
    $advisor_firstname = $advisor_array[1];
    $advisor_lastname = $advisor_array[2];
}

$coAdvisor_prefix = '';
$coAdvisor_firstname = '';
$coAdvisor_lastname = '';
if ($_POST['coAdvisor'] != '') {
    $coAdvisor = $_POST['coAdvisor'];
    if ($coAdvisor === 'other') {
        $coAdvisor_prefix = $_POST['coAdvisor_other_prefix'];
        $coAdvisor_firstname = $_POST['coAdvisor_other_firstname'];
        $coAdvisor_lastname = $_POST['coAdvisor_other_lastname'];
    } else {
        $coAdvisor_array = explode(' ', $coAdvisor);
        $coAdvisor_prefix = $coAdvisor_array[0];
        $coAdvisor_firstname = $coAdvisor_array[1];
        $coAdvisor_lastname = $coAdvisor_array[2];
    }
}

$chairman = $_POST['chairman'];
if ($chairman === 'other') {
    $chairman_prefix = $_POST['chairman_other_prefix'];
    $chairman_firstname = $_POST['chairman_other_firstname'];
    $chairman_lastname = $_POST['chairman_other_lastname'];
} else {
    $chairman_array = explode(' ', $chairman);
    $chairman_prefix = $chairman_array[0];
    $chairman_firstname = $chairman_array[1];
    $chairman_lastname = $chairman_array[2];
}

$director1 = $_POST['director1'];
if ($director1 === 'other') {
    $director1_prefix = $_POST['director1_other_prefix'];
    $director1_firstname = $_POST['director1_other_firstname'];
    $director1_lastname = $_POST['director1_other_lastname'];
} else {
    $director1_array = explode(' ', $director1);
    $director1_prefix = $director1_array[0];
    $director1_firstname = $director1_array[1];
    $director1_lastname = $director1_array[2];
}

$director2 = $_POST['director2'];
if ($director2 === 'other') {
    $director2_prefix = $_POST['director2_other_prefix'];
    $director2_firstname = $_POST['director2_other_firstname'];
    $director2_lastname = $_POST['director2_other_lastname'];
} else {
    $director2_array = explode(' ', $director2);
    $director2_prefix = $director2_array[0];
    $director2_firstname = $director2_array[1];
    $director2_lastname = $director2_array[2];
}

$semester = $_POST['semester'];
$approval_year = $_POST['approval_year'];
$printed_year = $_POST['printed_year'];


$keywordTxt = '';
if (isset($_POST['keywords'])) {
    $keywords = $_POST['keywords'];
    foreach ($keywords as $index => $keyword) {
        $keywordTxt .= $keyword;
        if ($index == count($keywords) - 1) {
        } else {
            $keywordTxt .=  ", ";
        }
    }
}

if ($_FILES['approval_file']['size'] !== 0) {
    $approval_tmp = $_FILES['approval_file']['tmp_name'];
    $approval_name = $_FILES['approval_file']['name'];
    $approval_upload_path = 'FileStorage/approval/' . $approval_name;
    move_uploaded_file($approval_tmp, $approval_upload_path);
}

if ($_FILES['thesis_file']['size'] !== 0) {
    $thesis_tmp = $_FILES['thesis_file']['tmp_name'];
    $thesis_name = $_FILES['thesis_file']['name'];
    $thesis_upload_path = 'FileStorage/thesis/' . $thesis_name;
    move_uploaded_file($thesis_tmp, $thesis_upload_path);
}

if ($_FILES['poster_file']['size'] !== 0) {
    $poster_temp = $_FILES['poster_file']['tmp_name'];
    $poster_name = $_FILES['poster_file']['name'];
    $poster_upload_path = 'FileStorage/poster/' . $poster_name;
    move_uploaded_file($poster_temp, $poster_upload_path);
}

try {
    if ($_FILES['approval_file']['size'] !== 0 and $_FILES['thesis_file']['size'] !== 0 and $_FILES['poster_file']['size'] !== 0) {
        $update =  $conn->prepare("UPDATE thesis_document SET thai_name = :thai_name, english_name = :english_name, 
        abstract = :abstract, printed_year = :printed_year, semester = :semester, approval_year = :approval_year, 
        thesis_file = :thesis_file, approval_file = :approval_file, poster_file = :poster_file, keyword = :keyword, 
        prefix_chairman = :prefix_chairman, name_chairman = :name_chairman, surname_chairman = :surname_chairman, 
        prefix_director1 = :prefix_director1, name_director1 = :name_director1, surname_director1 = :surname_director1, 
        prefix_director2 = :prefix_director2, name_director2 = :name_director2, surname_director2 = :surname_director2,
        prefix_advisor = :prefix_advisor, name_advisor = :name_advisor, surname_advisor = :surname_advisor, 
        prefix_coAdvisor = :prefix_coAdvisor, name_coAdvisor = :name_coAdvisor, surname_coAdvisor = :surname_coAdvisor,
        thesis_file = :thesis_file, approval_file = :approval_file, poster_file = :poster_file
        WHERE thesis_id = :id");
        $update->bindParam(":id", $id);
        $update->bindParam(":thai_name", $thesis_name_th, PDO::PARAM_STR);
        $update->bindParam(":english_name", $thesis_name_en, PDO::PARAM_STR);
        $update->bindParam(":abstract", $abstract, PDO::PARAM_STR);
        $update->bindParam(":printed_year", $printed_year, PDO::PARAM_STR);
        $update->bindParam(":semester", $semester, PDO::PARAM_STR);
        $update->bindParam(":approval_year", $approval_year, PDO::PARAM_STR);
        $update->bindParam(":thesis_file", $thesis_upload_path, PDO::PARAM_STR);
        $update->bindParam(":approval_file", $approval_upload_path, PDO::PARAM_STR);
        $update->bindParam(":poster_file", $poster_upload_path, PDO::PARAM_STR);
        $update->bindParam(":keyword", $keyword, PDO::PARAM_STR);
        $update->bindParam(":prefix_chairman", $chairman_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_chairman", $chairman_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_chairman", $chairman_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_director1", $director1_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_director1", $director1_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_director1", $director1_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_director2", $director2_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_director2", $director2_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_director2", $director2_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_advisor", $advisor_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_advisor", $advisor_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_advisor", $advisor_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_coAdvisor", $coAdvisor_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_coAdvisor", $coAdvisor_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_coAdvisor", $coAdvisor_lastname, PDO::PARAM_STR);
        $update->bindParam(":thesis_file", $thesis_upload_path, PDO::PARAM_STR);
        $update->bindParam(":approval_file", $approval_upload_path, PDO::PARAM_STR);
        $update->bindParam(":poster_file", $poster_upload_path, PDO::PARAM_STR);
        // $result = $update->execute([$thesis_name_th, $thesis_name_en, $abstract, $printed_year, $semester, $approval_year, $thesis_upload_path, $approval_upload_path, $poster_upload_path, $keyword, ]);
    } else {
        $sql = "UPDATE thesis_document SET thai_name = :thai_name, english_name = :english_name, 
        abstract = :abstract, printed_year = :printed_year, semester = :semester, approval_year = :approval_year, keyword = :keyword, 
        prefix_chairman = :prefix_chairman, name_chairman = :name_chairman, surname_chairman = :surname_chairman, 
        prefix_director1 = :prefix_director1, name_director1 = :name_director1, surname_director1 = :surname_director1, 
        prefix_director2 = :prefix_director2, name_director2 = :name_director2, surname_director2 = :surname_director2,
        prefix_advisor = :prefix_advisor, name_advisor = :name_advisor, surname_advisor = :surname_advisor, 
        prefix_coAdvisor = :prefix_coAdvisor, name_coAdvisor = :name_coAdvisor, surname_coAdvisor = :surname_coAdvisor
        ";
        if ($_FILES['approval_file']['size'] !== 0) {
            $sql .= ", approval_file = \"$approval_upload_path\"";
        }
        if ($_FILES['poster_file']['size'] !== 0) {
            $sql .= ", poster_file = \"$poster_upload_path\"";
        }
        if ($_FILES['thesis_file']['size'] !== 0) {
            $sql .= ", thesis_file = \"$thesis_upload_path\"";
        }
        $sql .= " WHERE thesis_id = :id";
        echo $sql;
        $update =  $conn->prepare($sql);
        $update->bindParam(":id", $id);
        $update->bindParam(":thai_name", $thesis_name_th, PDO::PARAM_STR);
        $update->bindParam(":english_name", $thesis_name_en, PDO::PARAM_STR);
        $update->bindParam(":abstract", $abstract, PDO::PARAM_STR);
        $update->bindParam(":printed_year", $printed_year, PDO::PARAM_STR);
        $update->bindParam(":semester", $semester, PDO::PARAM_STR);
        $update->bindParam(":approval_year", $approval_year, PDO::PARAM_STR);
        $update->bindParam(":keyword", $keywordTxt, PDO::PARAM_STR);
        $update->bindParam(":prefix_chairman", $chairman_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_chairman", $chairman_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_chairman", $chairman_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_director1", $director1_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_director1", $director1_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_director1", $director1_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_director2", $director2_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_director2", $director2_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_director2", $director2_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_advisor", $advisor_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_advisor", $advisor_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_advisor", $advisor_lastname, PDO::PARAM_STR);
        $update->bindParam(":prefix_coAdvisor", $coAdvisor_prefix, PDO::PARAM_STR);
        $update->bindParam(":name_coAdvisor", $coAdvisor_firstname, PDO::PARAM_STR);
        $update->bindParam(":surname_coAdvisor", $coAdvisor_lastname, PDO::PARAM_STR);
    }
    $result = $update->execute();
    if ($result) {
        echo "แก้ข้อมูลเล่มสำเร็จ";
        $lastId = $conn->lastInsertId();
        $thesisId = $lastId;
        if (isset($_POST['member1'])) {
            $order = 1;
            $updateMem = $conn->prepare("UPDATE author_thesis SET student_id = :student_id, prefix = :prefix, name = :name, lastname = :lastname
                                WHERE thesis_id = :id AND order_member = :order");
            $updateMem->bindParam(":student_id", $member1_id);
            $updateMem->bindParam(":prefix", $member1_prefix);
            $updateMem->bindParam(":name", $member1_firstname);
            $updateMem->bindParam(":lastname", $member1_lastname);
            $updateMem->bindParam(":order", $order);
            $updateMem->bindParam(":id", $id);
            $result = $updateMem->execute();

            if ($result) {
                // echo "เพิ่มสมาชิก 1 สำเร็จ";
            }
        }
        if (isset($_POST['member2'])) {
            $order = 2;
            $updateMem = $conn->prepare("UPDATE author_thesis SET student_id = :student_id, prefix = :prefix, name = :name, lastname = :lastname
                                 WHERE thesis_id = :id AND order_member = :order");
            $updateMem->bindParam(":student_id", $member2_id);
            $updateMem->bindParam(":prefix", $member2_prefix);
            $updateMem->bindParam(":name", $member2_firstname);
            $updateMem->bindParam(":lastname", $member2_lastname);
            $updateMem->bindParam(":order", $order);
            $updateMem->bindParam(":id", $id);
            $result = $updateMem->execute();

            if ($result) {
                // echo "เพิ่มสมาชิก 2 สำเร็จ";
            }
        }
        if (isset($_POST['member3'])) {
            $order = 3;
            $updateMem = $conn->prepare("UPDATE author_thesis SET student_id = :student_id, prefix = :prefix, name = :name, lastname = :lastname
                                  WHERE thesis_id = :id AND order_member = :order");
            $updateMem->bindParam(":student_id", $member3_id);
            $updateMem->bindParam(":prefix", $member3_prefix);
            $updateMem->bindParam(":name", $member3_firstname);
            $updateMem->bindParam(":lastname", $member3_lastname);
            $updateMem->bindParam(":order", $order);
            $updateMem->bindParam(":id", $id);
            $result = $updateMem->execute();

            if ($result) {
                // echo "เพิ่มสมาชิก 3 สำเร็จ";
            }
        }

        session_start();        
        $urlLocation = ($_SESSION['role'] == 3) ? '/FinalProj/thesislistwaiting' : '/FinalProj/thesis?id=' . $id;
        header("location: $urlLocation");
    }
} catch (PDOException $e) {
    echo $e;
}
