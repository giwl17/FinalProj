<?php
require "dbconnect.php";
if (isset($_POST['selectShow'])) {
    $per_page_record = $_POST['selectShow'];
} else {
    $per_page_record = 10;
}
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $per_page_record;
$stmt = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY thesis_id DESC LIMIT {$start_from}, {$per_page_record} ");
$result = $stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);

//count total row
$selectAll = $conn->prepare("SELECT COUNT(*) as count FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1");
$selectAll->execute();
$row =  $selectAll->fetch();
$all_result =  $row['count'];

$html = "";
$html .=
    <<<HTML
    <div class='d-flex flex-column gap-3' id="thesis_list">
    HTML;
if ($stmt->rowCount() > 0) {
    foreach ($result as $row) {
        $html .=
            <<<HTML
                    <div class='border p-3 d-flex flex-column rounded-3 shadow-sm'>
                        <a class='text-dark' id='thesisName' href='thesis?id=$row->thesis_id'>
                        <div class='fw-bold'>$row->thai_name</div>
                        <div class='fw-bold'>$row->english_name</div>
                        </a>
                        <div>คณะผู้จัดทำ           
            HTML;

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
        if ($row->prefix_coAdvisor != '') {
            $html .= ", ";
            $html .= "<a href='search?coAdvisor=$row->prefix_coAdvisor$u$row->name_coAdvisor$u$row->surname_coAdvisor' class='link-primary' style='text-decoration:none;'>$row->prefix_coAdvisor $row->name_coAdvisor $row->surname_coAdvisor</a>";
        }
        $html .= "</div>";

        $keyword = explode(", ", $row->keyword);
        $html .= "<div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp";
        for ($i = 0; $i < count($keyword); $i++) {
            $html .= "<a style='text-decoration:none;' href='search?keyword=$keyword[$i]'>$keyword[$i]</a>";
            if (!($i == count($keyword) - 1)) {
                $html .= ",&nbsp";
            }
        }
        $html .= "</div>";

        $html .= "<div>ปีที่พิมพ์เล่ม <a href='search?printed=$row->printed_year' class='link-primary' style='text-decoration:none;'>$row->printed_year</a></div>";
        $html .= "</div>";
    }
}
$html .= "</div>";
echo $html;
echo "<nav class='d-flex justify-content-center'>";
echo "<ul class='pagination d-flex flex-wrap' id='pagination'>";
$total_pages = ceil($all_result / $per_page_record);
$pagLink = "";

if ($page >= 2) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>  < </a></li>";
}

for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        $pagLink .= "<li class='page-item active mb-1'>
        <a class='page-link' href='?page="
            . $i . "'>" . $i . " </a>
        </li>";
    } else {
        $pagLink .= "<li class='page-item'>
        <a class='page-link' href='?page=" . $i . "'>   
                                    " . $i . " </a>
        </li>";
    }
};
echo $pagLink;

if ($page < $total_pages) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>  > </a></li>";
}
echo "</ul>";
echo "</nav>";
?>

<script>
    function ShowThesis(event) {
        let showPageLimit = event.target.value
        ShowPageBar();
        let htmlText = ""
        const url = "thesis_api?start=" + <?= $start_from ?> + "&perpage=" + showPageLimit;
        console.log(url)
        fetch(url, {
            method: "GET",
        }).then(res => {
            return res.json()
        }).then(data => {
            data.forEach(item => {
                let member_length = Object.keys(item.author_member).length
                htmlText += `
                <div class='border p-3 d-flex flex-column rounded-3 shadow-sm'>
                        <a class='text-dark' id='thesisName' href='thesis?id=${item.thesis_id}'>
                        <div class='fw-bold'>${item.thai_name}</div>
                        <div class='fw-bold'>${item.english_name}</div>
                        </a>
                `
                htmlText += "<div>คณะผู้จัดทำ "
                let count = 1;
                for (const key in item.author_member) {
                    let nameAuthor = item.author_member[key].prefix + item.author_member[key].name + " " + item.author_member[key].lastname;
                    htmlText += `<div class='d-inline'>${nameAuthor}</div>`
                    if (count < member_length) {
                        htmlText += "<span class='text-dark'>, </span>";
                    }
                    count++
                }
                htmlText += "</div>"
                htmlText += `<div>อาจารยที่ปรึกษา <a href='search?advisor=${item.prefix_advisor}_${item.name_advisor}_${item.surname_advisor}' class='link-primary' style='text-decoration:none;'>${item.prefix_advisor} ${item.name_advisor} ${item.surname_advisor}</a>`

                if (item.name_coAdvisor !== '') {
                    htmlText += ", "
                    htmlText += `<a href='search?coAdvisor=${item.prefix_coAdvisor}_${item.name_coAdvisor}_${item.surname_coAdvisor}' class='link-primary' style='text-decoration:none;'>${item.prefix_coAdvisor} ${item.name_coAdvisor} ${item.surname_coAdvisor}</a>`

                }
                htmlText += "</div>"

                let keyword = item.keyword.split(", ")
                htmlText += "<div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp"
                for (let i = 0; i < keyword.length; i++) {
                    htmlText += `<a style='text-decoration:none;' href='search?keyword=${keyword[i]}'>${keyword[i]}</a>`
                    if (i != keyword.length) {
                        htmlText += ",&nbsp"
                    }
                }
                htmlText += "</div>"

                htmlText += `<div>ปีที่พิมพ์เล่ม <a href='search?printed=${item.printed_year}' class='link-primary' style='text-decoration:none;'>${item.printed_year}</a></div>`

                htmlText += "</div>"
                document.querySelector("#thesis_list").innerHTML = htmlText;
            });

        })

    }

    function ShowPageBar() {
        let pageBar = document.querySelector("#pagination")
        pageBar.innerHTML = '<p>123</p>'
    }
</script>