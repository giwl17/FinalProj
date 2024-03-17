<?php

require "dbconnect.php";

//check page select
if (isset($_POST["page"])) {
    $page  = $_POST["page"];
} else {
    $page = 1;
}
//count total record
$query = "SELECT COUNT(*) as count FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1";
$selectAll = $conn->prepare($query);
$selectAll->execute();
$row_count =  $selectAll->fetch();
$all_result =  $row_count['count'];
//define variable page
$per_page_record = 10;
$start_from = ($page - 1) * $per_page_record;
$total_pages = ceil($all_result / $per_page_record);

//query thesis
if (isset($_POST['selectSortBy'])) {
    if ($_POST['selectSortBy'] == 'sort_printedYear_new') {
        $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year DESC LIMIT $start_from, $per_page_record";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if ($_POST['selectSortBy'] == 'sort_printedYear_old') {
        $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year ASC LIMIT $start_from, $per_page_record";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year DESC LIMIT $start_from, $per_page_record";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    $selectSortBy = $_POST['selectSortBy'];
} else {
    $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year DESC LIMIT $start_from, $per_page_record";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $selectSortBy = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการปริญญานิพนธ์</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
    <?php require 'template/header.php'; ?>
    <div class='container d-flex flex-column my-5 gap-3 position-relative'>
        <div class="d-flex flex-column">
            <form class="d-flex position-relative" action="search.php">
                <label class="position-absolute" style="top: -1.5rem;">ค้นหารายการจาก</label>
                <select name="selected" id="selectSearch" class="form-select rounded-start-3 rounded-end-0 w-auto">
                    <option value="all" selected>ทั้งหมด</option>
                    <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                    <option value="keyword">คำสำคัญ</option>
                    <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                    <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                    <option value="abstract">บทคัดย่อ</option>
                    <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>

                <div class="flex-grow-1 position-relative">
                    <input type="text" name="data" id="inputSearch" class="form-control rounded-end-3 rounded-start-0" placeholder="">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>
                <button class="btn rounded-0 col-auto position-absolute end-0"><i class="bi bi-search px-1"></i></button>
            </form>
            <a href='/FinalProj/search/advance' class="text-end mt-2 link-dark">การค้นหาขัั้นสูง</a>
        </div>

        <!-- display select sort -->
        <form method="POST" action="" id="formSort">
            <div class='d-flex align-items-center justify-content-end w-100 gap-2'>
                <label class='w-auto' for='sortBy'>เรียงจาก</label>
                <select class='form-select w-auto' id='selectSortBy' name='selectSortBy' onchange="ThesisGetData()">
                    <option value="sort_printedYear_new" <?php if ($selectSortBy == 'sort_printedYear_new') echo "selected"; ?>>ปีที่ตีพิมพ์เล่ม ใหม่->เก่า</option>
                    <option value="sort_printedYear_old" <?php if ($selectSortBy == 'sort_printedYear_old') echo "selected"; ?>>ปีที่ตีพิมพ์เล่ม เก่า->ใหม่</option>
                    <option value="sort_englishName_first" <?php if ($selectSortBy == 'sort_englishName_first') echo "selected"; ?>>ชื่อปริญญานิพนธ์ภาษาอังกฤษ A->Z</option>
                    <option value="sort_englishName_end" <?php if ($selectSortBy == 'sort_englishName_end') echo "selected"; ?>>ชื่อปริญญานิพนธ์ภาษาอังกฤษ Z->A</option>
                    <option value="sort_thaiName_first" <?php if ($selectSortBy == 'sort_thaiName_first') echo "selected"; ?>>ชื่อปริญญานิพนธ์ภาษาไทย ก->ฮ</option>
                    <option value="sort_thaiName_end" <?php if ($selectSortBy == 'sort_thaiName_end') echo "selected"; ?>>ชื่อปริญญานิพนธ์ภาษาไทย ฮ->ก</option>
                </select>
            </div>
        </form>

        <!-- display list of thesis -->
        <div class='d-flex flex-column gap-3' id='thesis_list'>
            <?php if ($stmt->rowCount() > 0) : ?>

                <?php foreach ($result as $row) : ?>

                    <?php
                    $keyword = explode(", ", $row['keyword']);
                    //query author
                    $query = "SELECT * FROM author_thesis WHERE thesis_id = $row[thesis_id]";
                    $selectMem = $conn->prepare($query);
                    $selectMem->execute();
                    $result_selectMem = $selectMem->fetchAll(PDO::FETCH_ASSOC);
                    $count = count($result_selectMem);
                    $i = 1;
                    $u = '_';
                    ?>

                    <div class='border p-3 d-flex flex-column rounded-3 shadow-sm'>
                        <a class='text-dark' id='thesisName' href='thesis?id=<?= $row['thesis_id'] ?>'>
                            <div class='fw-bold'><?= $row['thai_name'] ?></div>
                            <div class='fw-bold'><?= $row['english_name'] ?></div>
                        </a>

                        <div>คณะผู้จัดทำ
                            <?php foreach ($result_selectMem as $mem) : ?>
                                <?php $nameAuthor = $mem['prefix'] . $mem['name'] . " " . $mem['lastname']; ?>
                                <div class='d-inline'><?= $nameAuthor ?></div>
                                <?php if ($count != $i++) : ?>
                                    <span class='text-dark'>,&nbsp;</span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div>อาจารยที่ปรึกษา <a href='search?advisor=<?= $row['prefix_advisor'] ?><?= $u ?><?= $row['name_advisor'] ?><?= $u ?><?= $row['surname_advisor'] ?>' class='link-primary' style='text-decoration:none;'><?= $row['prefix_advisor'] ?> <?= $row['name_advisor'] ?> <?= $row['surname_advisor'] ?></a>
                            <?php if ($row['prefix_coAdvisor'] != '') : ?>
                                ,&nbsp;
                                <a href='search?coAdvisor=<?= $row['prefix_coAdvisor'] ?><?= $u ?><?= $row['name_coAdvisor'] ?><?= $u ?><?= $row['surname_coAdvisor'] ?>' class='link-primary' style='text-decoration:none;'><?= $row['prefix_coAdvisor'] ?> <?= $row['name_coAdvisor'] ?> <?= $row['surname_coAdvisor'] ?></a>
                                }
                            <?php endif; ?>
                        </div>

                        <div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp;
                            <?php for ($i = 0; $i < count($keyword); $i++) : ?>
                                <a style='text-decoration:none;' href='search?keyword=<?= $keyword[$i] ?>'><?= $keyword[$i] ?></a>
                                <?php if (!($i == count($keyword) - 1)) : ?>
                                    ,&nbsp;
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <div>ปีที่พิมพ์เล่ม <a href='search?printed=<?= $row['printed_year'] ?>' class='link-primary' style='text-decoration:none;'><?= $row['printed_year'] ?></a></div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <!-- display menu page -->
        <nav class='d-flex justify-content-center'>
            <ul class='pagination d-flex flex-wrap' id='pagination'>

                <?php if ($page >= 2) : ?>
                    <form action="" method="POST">
                        <li class='page-item'>
                            <input type="hidden" name="page" value="<?= ($page - 1) ?>">
                            <button class='page-link rounded-0 rounded-start-3 '>
                                < </button>
                        </li>
                    </form>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <?php if ($i == $page) : ?>
                        <form action="" method="POST">
                            <li class='page-item active mb-1'>
                                <input type="hidden" name="page" value="<?= $i ?>">
                                <button class='page-link rounded-0'> <?= $i ?> </button>
                            </li>
                        </form>
                    <?php else : ?>
                        <form action="" method="POST">
                            <li class='page-item'>
                                <input type="hidden" name="page" value="<?= $i ?>">
                                <button class='page-link rounded-0'> <?= $i ?> </button>
                            </li>
                        </form>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages) : ?>
                    <form action="" method="POST">
                        <li class='page-item'>
                            <input type="hidden" name="page" value="<?= ($page + 1) ?>">
                            <button class='page-link rounded-0 rounded-end-3'> > </button>
                        </li>
                    </form>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
    <script>
        function submitSearch() {
            let selectSearch = document.getElementById('selectSearch').value;
            let inputSearch = document.getElementById('inputSearch');
        }

        inputSearch.addEventListener('keyup', () => {
            let input = inputSearch.value;
            let searchingDOM = document.getElementById('searching');

            if (input != '') {
                searchingDOM.classList.remove('d-none');

                let options = {
                    method: 'GET',
                    input: input,
                }
                let url = '/FinalProj/searchbar_db?data=' + input + "&selected=" + selectSearch.value;
                fetch(url, options)
                    .then(response => {
                        return response.text()
                    })
                    .then(data => searchingDOM.innerHTML = data)
            } else {
                searchingDOM.classList.add('d-none');
                searchingDOM.innerHTML = "";
            }
        });

        let sort = document.getElementById('selectSortBy').value;

        function ThesisGetData() {
            sort = document.getElementById('selectSortBy').value;
            let url = "./api/thesis.php?sort=" + sort;
            fetch(url, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    },
                })
                .then(res => {
                    return res.json();
                })
                .then(data => {
                    let thesisList = document.getElementById('thesis_list');
                    let htmlTxt = "";

                    data.forEach((row) => {
                        htmlTxt += `<div class='border p-3 d-flex flex-column rounded-3 shadow-sm'>`;
                        htmlTxt += `
                        <a class='text-dark' id='thesisName' href='thesis?id=${row.thesis_id}'>
                            <div class='fw-bold'>${row.thai_name}</div>
                            <div class='fw-bold'>${row.english_name}</div>
                        </a>`;

                        const count_member = Object.keys(row.author_member).length;
                        let i = 0;
                        htmlTxt += `<div>คณะผู้จัดทำ `;
                        for (const key in row.author_member) {
                            let nameAuthor = row.author_member[key].prefix + row.author_member[key].name + " " + row.author_member[key].lastname;
                            htmlTxt += `<div class='d-inline'>${nameAuthor}</div>`;
                            if (i < count_member - 1) {
                                htmlTxt += `<span class='text-dark'>,&nbsp;</span>`;
                            }
                            i++;
                        }
                        htmlTxt += `</div>`;

                        htmlTxt += `<div>อาจารยที่ปรึกษา <a href='search?advisor=${row.prefix_advisor}_${row.name_advisor}_${row.surname_advisor}' class='link-primary' style='text-decoration:none;'>${row.prefix_advisor} ${row.name_advisor} ${row.surname_advisor}</a>`;
                        if (row.prefix_coAdvisor != '') {
                            htmlTxt += ",&nbsp;";
                            htmlTxt += `<a href='search?coAdvisor=${row.prefix_coAdvisor}_${row.name_coAdvisor}_${row.surname_coAdvisor}' class='link-primary' style='text-decoration:none;'>${row.prefix_coAdvisor} ${row.name_coAdvisor} ${row.surname_coAdvisor}</a>`;
                        }
                        htmlTxt += "</div>";

                        let keyword = row.keyword.split(", ");
                        htmlTxt += `<div class='col-auto d-flex flex-row'>คำสำคัญ&nbsp;`;
                        for (let i = 0; i < keyword.length; i++) {
                            htmlTxt += `<a style='text-decoration:none;' href='search?keyword=${keyword[i]}'>${keyword[i]}</a>`;
                            if (!(i == keyword.length - 1)) {
                                htmlTxt += ",&nbsp;";
                            }
                        }
                        htmlTxt += "</div>";

                        htmlTxt += ` <div>ปีที่พิมพ์เล่ม <a href='search?printed=${row.printed_year}' class='link-primary' style='text-decoration:none;'>${row.printed_year}</a></div>`;

                        htmlTxt += `</div>`;
                    });

                    thesisList.innerHTML = htmlTxt;
                });
        }
    </script>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/106a60ac58.js" crossorigin="anonymous"></script>

</body>

</html>