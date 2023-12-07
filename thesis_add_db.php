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


    $i = 1;
    while (true) {
        if (isset($_POST['keyword_' . $i])) {
            ${"keyword_" . $i} = $_POST['keyword_' . $i];
            // echo ${"keyword_" . $i};
            $i++;
        } else {
            break;
        }
    }

    $approval_file = $_POST['approval_file'];
    $thesis_file = $_POST['thesis_file'];
    $poster_file = $_POST['poster_file'];
}
