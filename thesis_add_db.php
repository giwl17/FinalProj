<?php
session_start();
require_once 'dbconnect.php';
if (isset($_POST['submitAddThesis'])) {
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


    if (isset($_POST['keyword'])) {
        $keywordInput = $_POST['keyword'];
        $keywords = "";

        for ($i = 0; $i < count($keywordInput); $i++) {
            $keywords .= $keywordInput[$i];
            if (!($i == (count($keywordInput) - 1))) {
                $keywords .= ", ";
            }
        }
    }

    $approval_tmp = $_FILES['approval_file']['tmp_name'];
    $approval_name = $_FILES['approval_file']['name'];
    $approval_upload_path = 'FileStorage/approval/' . $approval_name;
    move_uploaded_file($approval_tmp, $approval_upload_path);

    $thesis_tmp = $_FILES['thesis_file']['tmp_name'];
    $thesis_name = $_FILES['thesis_file']['name'];
    $thesis_upload_path = 'FileStorage/thesis/' . $thesis_name;
    move_uploaded_file($thesis_tmp, $thesis_upload_path);


    $poster_temp = $_FILES['poster_file']['tmp_name'];
    $poster_name = $_FILES['poster_file']['name'];
    $poster_upload_path = 'FileStorage/poster/' . $poster_name;
    move_uploaded_file($poster_temp, $poster_upload_path);

    $thesis_status = 1; // 0 = ไม่เผยแพร่, 1 = เผยแพร่, 2 = Archove
    $approval_status = 1; // 0 = รออนุมัติ, 1 = อนุมัติ

    try {
        $insert =  $conn->prepare("INSERT INTO thesis_document(thai_name, english_name, abstract, printed_year, semester, approval_year, thesis_file, approval_file, poster_file, keyword, prefix_chairman, name_chairman, surname_chairman, prefix_director1, name_director1, surname_director1, prefix_director2, name_director2, surname_director2, prefix_advisor, name_advisor, surname_advisor, prefix_coAdvisor, name_coAdvisor, surname_coAdvisor, thesis_status, approval_status)
                                VALUES(:thai_name, :english_name, :abstract, :printed_year, :semester, :approval_year, :thesis_file, :approval_file, :poster_file, :keyword, :prefix_chairman, :name_chairman, :surname_chairman, :prefix_director1, :name_director1, :surname_director1, :prefix_director2, :name_director2, :surname_director2, :prefix_advisor, :name_advisor, :surname_advisor, :prefix_coAdvisor, :name_coAdvisor, :surname_coAdvisor, :thesis_status, :approval_status) ");
        $insert->bindParam(":thai_name", $thesis_name_th, PDO::PARAM_STR);
        $insert->bindParam(":english_name", $thesis_name_en, PDO::PARAM_STR);
        $insert->bindParam(":abstract", $abstract, PDO::PARAM_STR);
        $insert->bindParam(":printed_year", $printed_year, PDO::PARAM_STR);
        $insert->bindParam(":semester", $semester, PDO::PARAM_STR);
        $insert->bindParam(":approval_year", $approval_year, PDO::PARAM_STR);
        $insert->bindParam(":thesis_file", $thesis_upload_path, PDO::PARAM_STR);
        $insert->bindParam(":approval_file", $approval_upload_path, PDO::PARAM_STR);
        $insert->bindParam(":poster_file", $poster_upload_path, PDO::PARAM_STR);
        $insert->bindParam(":keyword", $keywords, PDO::PARAM_STR);
        $insert->bindParam(":prefix_chairman", $chairman_prefix, PDO::PARAM_STR);
        $insert->bindParam(":name_chairman", $chairman_firstname, PDO::PARAM_STR);
        $insert->bindParam(":surname_chairman", $chairman_lastname, PDO::PARAM_STR);
        $insert->bindParam(":prefix_director1", $director1_prefix, PDO::PARAM_STR);
        $insert->bindParam(":name_director1", $director1_firstname, PDO::PARAM_STR);
        $insert->bindParam(":surname_director1", $director1_lastname, PDO::PARAM_STR);
        $insert->bindParam(":prefix_director2", $director2_prefix, PDO::PARAM_STR);
        $insert->bindParam(":name_director2", $director2_firstname, PDO::PARAM_STR);
        $insert->bindParam(":surname_director2", $director2_lastname, PDO::PARAM_STR);
        $insert->bindParam(":prefix_advisor", $advisor_prefix, PDO::PARAM_STR);
        $insert->bindParam(":name_advisor", $advisor_firstname, PDO::PARAM_STR);
        $insert->bindParam(":surname_advisor", $advisor_lastname, PDO::PARAM_STR);
        $insert->bindParam(":prefix_coAdvisor", $coAdvisor_prefix, PDO::PARAM_STR);
        $insert->bindParam(":name_coAdvisor", $coAdvisor_firstname, PDO::PARAM_STR);
        $insert->bindParam(":surname_coAdvisor", $coAdvisor_lastname, PDO::PARAM_STR);
        $insert->bindParam(":thesis_status", $thesis_status);
        $insert->bindParam(":approval_status", $approval_status);

        $result = $insert->execute();
        if ($result) {
            // echo "เพิ่มข้อมูลเล่มสำเร็จ";
            $lastId = $conn->lastInsertId();
            $thesisId = $lastId;
            if (isset($_POST['member1'])) {
                $order = 1;
                $insertMem = $conn->prepare("INSERT INTO author_thesis(student_id, prefix, name, lastname, thesis_id)
                                VALUES(:student_id, :prefix, :name, :lastname, :thesis_id) ");
                $insertMem->bindParam(":student_id", $member1_id);
                $insertMem->bindParam(":prefix", $member1_prefix);
                $insertMem->bindParam(":name", $member1_firstname);
                $insertMem->bindParam(":lastname", $member1_lastname);
                $insertMem->bindParam(":thesis_id", $thesisId);
                $result = $insertMem->execute();
            }
            if (isset($_POST['member2'])) {
                $order = 2;
                $insertMem = $conn->prepare("INSERT INTO author_thesis(student_id, prefix, name, lastname, thesis_id)
                                VALUES(:student_id, :prefix, :name, :lastname, :thesis_id)");
                $insertMem->bindParam(":student_id", $member2_id);
                $insertMem->bindParam(":prefix", $member2_prefix);
                $insertMem->bindParam(":name", $member2_firstname);
                $insertMem->bindParam(":lastname", $member2_lastname);
                $insertMem->bindParam(":thesis_id", $thesisId);
                $result = $insertMem->execute();
            }
            if (isset($_POST['member3'])) {
                $order = 3;
                $insertMem = $conn->prepare("INSERT INTO author_thesis(student_id, prefix, name, lastname, thesis_id)
                                VALUES(:student_id, :prefix, :name, :lastname, :thesis_id)");
                $insertMem->bindParam(":student_id", $member3_id);
                $insertMem->bindParam(":prefix", $member3_prefix);
                $insertMem->bindParam(":name", $member3_firstname);
                $insertMem->bindParam(":lastname", $member3_lastname);
                $insertMem->bindParam(":thesis_id", $thesisId);
                $result = $insertMem->execute();
            }

            $_SESSION['insertDataSuccess'] = true;
            header('location: ./thesisadd');
        }
    } catch (PDOException $e) {
        echo $e;
    }
}
