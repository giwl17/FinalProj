<?php
    require_once "dbconnect.php";
    $select = "SELECT * FROM thesis_document WHERE thesis_status = 1 ORDER BY thesis_id DESC";
    $stmt = $conn->prepare($select);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    if ($stmt->rowCount() > 0) {
        foreach ($result as $row) {
            $html = "
                    <div class='border border-dark w-100 p-3 d-flex flex-column'>
                        <a class='text-dark' id='thesisName' href='thesis?id=$row->thesis_id'>
                        <div class='fw-bold'>$row->thai_name</div>
                        <div class='fw-bold'>$row->english_name</div>
                        </a>
                        <div>คณะผู้จัดทำ           
            ";

            $selectMem = $conn->prepare("SELECT * FROM author_thesis WHERE thesis_id = $row->thesis_id");
            $selectMem->execute();
            $result_selectMem = $selectMem->fetchAll(PDO::FETCH_OBJ);

            if ($selectMem->rowCount() > 0) {
                $count = count($result_selectMem);
                $i = 1;
                foreach ($result_selectMem as $mem) {
                    $nameAuthor = $mem->prefix . "" . $mem->name . " " . $mem->lastname;
                    $html .= "<div class='d-inline'>$nameAuthor</div>";
                    if ($count != $i++) {
                        $html .= "<span class='text-dark'>, </span>";
                    }
                }
            }
            $u = '_';
            $html .= "</div>";  
            $html .= "<div>อาจารยที่ปรึกษา <a href='search?advisor=$row->prefix_advisor$u$row->name_advisor$u$row->surname_advisor' class='link-primary' style='text-decoration:none;'>$row->prefix_advisor $row->name_advisor $row->surname_advisor</a>";
            if($row->prefix_coAdvisor != '') {
                $html .= ", ";
                $html .= "<a href='search?coAdvisor=$row->prefix_coAdvisor$u$row->name_coAdvisor$u$row->surname_coAdvisor' class='link-primary' style='text-decoration:none;'>$row->prefix_coAdvisor $row->name_coAdvisor $row->surname_coAdvisor</a>";
            }
            $html .= "</div>";

            $html .= "<div>คำสำคัญ <a href='#' class='link-primary' style='text-decoration:none;'>$row->keyword</a></div>";
            $html .= "<div>ปีที่พิมพ์เล่ม <a href='search?printed=$row->printed_year' class='link-primary' style='text-decoration:none;'>$row->printed_year</a></div>";
            $html .= "</div>";

            echo $html;
        }
      
    }
?>