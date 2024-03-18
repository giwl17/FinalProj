<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(trim(file_get_contents("php://input")));
require "../dbconnect.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['sort'] !== '' AND $_GET['select'] !== '' AND $_GET['data'] != '') {
        //have search
        $searchSelect = $_GET['select'];
        $likeInput = "%" . $_GET['data'] . "%";

        if ($_GET['sort'] == 'sort_printedYear_new') {
            $sort = 'printed_year';
            $order = 'DESC';
        } else if ($_GET['sort'] == 'sort_printedYear_old') {
            $sort = 'printed_year';
            $order = 'ASC';
        } else if ($_GET['sort'] == 'sort_englishName_first') {
            $sort = 'english_name';
            $order = 'ASC';
        } else if ($_GET['sort'] == 'sort_englishName_end') {
            $sort = 'english_name';
            $order = 'DESC';
        } else if ($_GET['sort'] == 'sort_thaiName_first') {
            $sort = 'thai_name';
            $order = 'ASC';
        } else if ($_GET['sort'] == 'sort_thaiName_end') {
            $sort = 'thai_name';
            $order = 'DESC';
        }

        if ($searchSelect == 'all') {           
            //select thesis
            $query = "SELECT * 
            FROM thesis_document
            WHERE (english_name LIKE :input)
            AND (thesis_status = 1 AND approval_status = 1)
            ";
            $query .= "ORDER BY " . $sort . " " . $order;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":input", $likeInput);
        $stmt->execute();
        $thesis = [];
        $index = 0;
        $i = 0;
        foreach ($stmt->fetchAll() as $row) {
            $select_mem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
            $select_mem->bindParam(":thesis_id", $row['thesis_id']);
            $select_mem->execute();
            $thesis[$index]['thesis_id'] = $row['thesis_id'];
            $thesis[$index]['thai_name'] = $row['thai_name'];
            $thesis[$index]['english_name'] = $row['english_name'];
            $thesis[$index]['abstract'] = $row['abstract'];
            $thesis[$index]['printed_year'] = $row['printed_year'];
            $thesis[$index]['semester'] = $row['semester'];
            $thesis[$index]['approval_year'] = $row['approval_year'];
            $thesis[$index]['thesis_file'] = $row['thesis_file'];
            $thesis[$index]['approval_file'] = $row['approval_file'];
            $thesis[$index]['poster_file'] = $row['poster_file'];
            $thesis[$index]['keyword'] = $row['keyword'];
            $thesis[$index]['prefix_chairman'] = $row['prefix_chairman'];
            $thesis[$index]['name_chairman'] = $row['name_chairman'];
            $thesis[$index]['surname_chairman'] = $row['surname_chairman'];
            $thesis[$index]['prefix_director1'] = $row['prefix_director1'];
            $thesis[$index]['name_director1'] = $row['name_director1'];
            $thesis[$index]['surname_director1'] = $row['surname_director1'];
            $thesis[$index]['prefix_director2'] = $row['prefix_director2'];
            $thesis[$index]['name_director2'] = $row['name_director2'];
            $thesis[$index]['surname_director2'] = $row['surname_director2'];
            $thesis[$index]['prefix_advisor'] = $row['prefix_advisor'];
            $thesis[$index]['name_advisor'] = $row['name_advisor'];
            $thesis[$index]['surname_advisor'] = $row['surname_advisor'];
            $thesis[$index]['prefix_coAdvisor'] = $row['prefix_coAdvisor'];
            $thesis[$index]['name_coAdvisor'] = $row['name_coAdvisor'];
            $thesis[$index]['surname_coAdvisor'] = $row['surname_coAdvisor'];
            $thesis[$index]['dateTime_import'] = $row['dateTime_import'];
            $thesis[$index]['dateTime_deleted'] = $row['dateTime_deleted'];
            foreach ($select_mem->fetchAll() as $mem) {
                $thesis[$index]['author_member']["member$i"]["student_id"] = $mem['student_id'];
                $thesis[$index]['author_member']["member$i"]["prefix"] = $mem['prefix'];
                $thesis[$index]['author_member']["member$i"]["name"] = $mem['name'];
                $thesis[$index]['author_member']["member$i"]["lastname"] = $mem['lastname'];
                $i++;
            }
            $i = 1;
            $index++;
        }

        echo json_encode($thesis, JSON_UNESCAPED_UNICODE);
        // echo json_encode(["data" => "a"], JSON_UNESCAPED_UNICODE);
    } else if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'sort_printedYear_new') {
            $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year DESC";
        } else if ($_GET['sort'] == 'sort_printedYear_old') {
            $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year";
        } else if ($_GET['sort'] == 'sort_englishName_first') {
            $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY english_name";
        } else if ($_GET['sort'] == 'sort_englishName_end') {
            $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY english_name DESC";
        } else if ($_GET['sort'] == 'sort_thaiName_first') {
            $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY thai_name";
        } else if ($_GET['sort'] == 'sort_thaiName_end') {
            $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY thai_name DESC";
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $thesis = [];
        $index = 0;
        $i = 0;
        foreach ($stmt->fetchAll() as $row) {
            $select_mem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
            $select_mem->bindParam(":thesis_id", $row['thesis_id']);
            $select_mem->execute();
            $thesis[$index]['thesis_id'] = $row['thesis_id'];
            $thesis[$index]['thai_name'] = $row['thai_name'];
            $thesis[$index]['english_name'] = $row['english_name'];
            $thesis[$index]['abstract'] = $row['abstract'];
            $thesis[$index]['printed_year'] = $row['printed_year'];
            $thesis[$index]['semester'] = $row['semester'];
            $thesis[$index]['approval_year'] = $row['approval_year'];
            $thesis[$index]['thesis_file'] = $row['thesis_file'];
            $thesis[$index]['approval_file'] = $row['approval_file'];
            $thesis[$index]['poster_file'] = $row['poster_file'];
            $thesis[$index]['keyword'] = $row['keyword'];
            $thesis[$index]['prefix_chairman'] = $row['prefix_chairman'];
            $thesis[$index]['name_chairman'] = $row['name_chairman'];
            $thesis[$index]['surname_chairman'] = $row['surname_chairman'];
            $thesis[$index]['prefix_director1'] = $row['prefix_director1'];
            $thesis[$index]['name_director1'] = $row['name_director1'];
            $thesis[$index]['surname_director1'] = $row['surname_director1'];
            $thesis[$index]['prefix_director2'] = $row['prefix_director2'];
            $thesis[$index]['name_director2'] = $row['name_director2'];
            $thesis[$index]['surname_director2'] = $row['surname_director2'];
            $thesis[$index]['prefix_advisor'] = $row['prefix_advisor'];
            $thesis[$index]['name_advisor'] = $row['name_advisor'];
            $thesis[$index]['surname_advisor'] = $row['surname_advisor'];
            $thesis[$index]['prefix_coAdvisor'] = $row['prefix_coAdvisor'];
            $thesis[$index]['name_coAdvisor'] = $row['name_coAdvisor'];
            $thesis[$index]['surname_coAdvisor'] = $row['surname_coAdvisor'];
            $thesis[$index]['dateTime_import'] = $row['dateTime_import'];
            $thesis[$index]['dateTime_deleted'] = $row['dateTime_deleted'];
            foreach ($select_mem->fetchAll() as $mem) {
                $thesis[$index]['author_member']["member$i"]["student_id"] = $mem['student_id'];
                $thesis[$index]['author_member']["member$i"]["prefix"] = $mem['prefix'];
                $thesis[$index]['author_member']["member$i"]["name"] = $mem['name'];
                $thesis[$index]['author_member']["member$i"]["lastname"] = $mem['lastname'];
                $i++;
            }
            $i = 1;
            $index++;
        }

        echo json_encode($thesis, JSON_UNESCAPED_UNICODE);
        // echo json_encode(['data' => "a"], JSON_UNESCAPED_UNICODE);
    } else {
        $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $thesis = [];
        $index = 0;
        $i = 0;
        foreach ($stmt->fetchAll() as $row) {
            $select_mem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = :thesis_id");
            $select_mem->bindParam(":thesis_id", $row['thesis_id']);
            $select_mem->execute();
            $thesis[$index]['thesis_id'] = $row['thesis_id'];
            $thesis[$index]['thai_name'] = $row['thai_name'];
            $thesis[$index]['english_name'] = $row['english_name'];
            $thesis[$index]['abstract'] = $row['abstract'];
            $thesis[$index]['printed_year'] = $row['printed_year'];
            $thesis[$index]['semester'] = $row['semester'];
            $thesis[$index]['approval_year'] = $row['approval_year'];
            $thesis[$index]['thesis_file'] = $row['thesis_file'];
            $thesis[$index]['approval_file'] = $row['approval_file'];
            $thesis[$index]['poster_file'] = $row['poster_file'];
            $thesis[$index]['keyword'] = $row['keyword'];
            $thesis[$index]['prefix_chairman'] = $row['prefix_chairman'];
            $thesis[$index]['name_chairman'] = $row['name_chairman'];
            $thesis[$index]['surname_chairman'] = $row['surname_chairman'];
            $thesis[$index]['prefix_director1'] = $row['prefix_director1'];
            $thesis[$index]['name_director1'] = $row['name_director1'];
            $thesis[$index]['surname_director1'] = $row['surname_director1'];
            $thesis[$index]['prefix_director2'] = $row['prefix_director2'];
            $thesis[$index]['name_director2'] = $row['name_director2'];
            $thesis[$index]['surname_director2'] = $row['surname_director2'];
            $thesis[$index]['prefix_advisor'] = $row['prefix_advisor'];
            $thesis[$index]['name_advisor'] = $row['name_advisor'];
            $thesis[$index]['surname_advisor'] = $row['surname_advisor'];
            $thesis[$index]['prefix_coAdvisor'] = $row['prefix_coAdvisor'];
            $thesis[$index]['name_coAdvisor'] = $row['name_coAdvisor'];
            $thesis[$index]['surname_coAdvisor'] = $row['surname_coAdvisor'];
            $thesis[$index]['dateTime_import'] = $row['dateTime_import'];
            $thesis[$index]['dateTime_deleted'] = $row['dateTime_deleted'];
            foreach ($select_mem->fetchAll() as $mem) {
                $thesis[$index]['author_member']["member$i"]["student_id"] = $mem['student_id'];
                $thesis[$index]['author_member']["member$i"]["prefix"] = $mem['prefix'];
                $thesis[$index]['author_member']["member$i"]["name"] = $mem['name'];
                $thesis[$index]['author_member']["member$i"]["lastname"] = $mem['lastname'];
                $i++;
            }
            $i = 1;
            $index++;
        }
        echo json_encode($thesis, JSON_UNESCAPED_UNICODE);
    }
}
