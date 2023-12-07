<?php
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
    $coAdvisor = $_POST['coAdvisor'];

    $chairman = $_POST['chairman'];
    $director1 = $_POST['director1'];
    $director2 = $_POST['director2'];

    $semester = $_POST['semester'];
    $approval_year = $_POST['approval_year'];
    $printed_year = $_POST['printed_year'];


    $keyword = '';
    $i = 1;
    while (true) {
        if (isset($_POST['keyword_' . $i])) {
            ${"keyword_" . $i} = $_POST['keyword_' . $i];
            // echo ${"keyword_" . $i};
            $keyword .= ${"keyword_" . $i} . ', ';
            $i++;
        } else {
            break;
        }
    }

    $approval_file = $_POST['approval_file'];
    $thesis_file = $_POST['thesis_file'];
    $poster_file = $_POST['poster_file'];

    try {
        $insert =  $conn->prepare("INSERT INTO thesis_document(thai_name, english_name, abstract, printed_year, semester, approval_year, thesis_file, approval_file, poster_file, keyword, prefix_chairman, name_chairman, surname_chairman, prefix_director1, name_director1, surname_director1, prefix_director2, name_director2, surname_director2, prefix_advisor, name_advisor, surname_advisor, prefix_coAdvisor, name_coAdvisor, surname_coAdvisor)
                                VALUES(:thai_name, :english_name, :abstract, :printed_year, :semester, :approval_year, :thesis_file, :approval_file, :poster_file, :keyword, :prefix_chairman, :name_chairman, :surname_chairman, :prefix_director1, :name_director1, :surname_director1, :prefix_director2, :name_director2, :surname_director2, :prefix_advisor, :name_advisor, :surname_advisor, :prefix_coAdvisor, :name_coAdvisor, :surname_coAdvisor) ");
        $insert->bindParam(":thai_name", $thesis_name_th);
        $insert->bindParam(":english_name", $thesis_name_en);
        $insert->bindParam(":abstract", $abstract);
        $insert->bindParam(":printed_year", $printed_year);
        $insert->bindParam(":semester", $semester);
        $insert->bindParam(":approval_year", $approval_year);
        $insert->bindParam(":thesis_file", $thesis_file);
        $insert->bindParam(":approval_file", $approval_file);
        $insert->bindParam(":poster_file", $poster_file);
        $insert->bindParam(":keyword", $keyword);
        $insert->bindParam(":prefix_chairman", $prefix_chairman);
        $insert->bindParam(":name_chairman", $name_chairman);
        $insert->bindParam(":surname_chairman", $surname_chairman);
        $insert->bindParam(":prefix_director1", $prefix_director1);
        $insert->bindParam(":name_director1", $name_director1);
        $insert->bindParam(":surname_director1", $surname_director1);
        $insert->bindParam(":prefix_director2", $prefix_director2);
        $insert->bindParam(":name_director2", $name_director2);
        $insert->bindParam(":surname_director2", $surname_director2);
        $insert->bindParam(":prefix_advisor", $prefix_advisor);
        $insert->bindParam(":name_advisor", $name_advisor);
        $insert->bindParam(":surname_advisor", $surname_advisor);
        $insert->bindParam(":prefix_coAdvisor", $prefix_coAdvisor);
        $insert->bindParam(":name_coAdvisor", $name_coAdvisor);
        $insert->bindParam(":surname_coAdvisor", $surname_coAdvisor);
        $insert->execute();
    } catch(PDOException $e) {
        echo $e;
    }
}
