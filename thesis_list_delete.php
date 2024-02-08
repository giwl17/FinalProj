<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลบหลายรายการ</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php require 'template/header.php'; ?>
    <div class='container d-flex flex-column my-5 gap-3 position-relative'>
        <h1 class="h3 text-center">รายการที่ต้องการลบ</h1>
        <?php
        require "dbconnect.php";
        if (isset($_POST['selectShow'])) {
            $per_page_record = $_POST['selectShow'];
        } else {
            $per_page_record = 5;
        }
        if (isset($_GET["page"])) {
            $page  = $_GET["page"];
        } else {
            $page = 1;
        }
        $start_from = ($page - 1) * $per_page_record;
        $stmt = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_status = 1 ORDER BY thesis_id DESC LIMIT {$start_from}, {$per_page_record} ");
        $result = $stmt->execute();
        ?>
        <form method="POST" id="formSelectPage" action="/FinalProj/thesisdelete">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <label class="" for="search">ค้นหาชื่อปริญญานิพนธ์</label>
                    <input class="form-control" type="search" name="search" id="search" onchange="searchThesis()">
                </div>
                <div>
                    <label class="" for="selectShow">แสดงรายการ</label>
                    <select class="form-select" id="selectShow" name="selectShow" onchange="showPages(this)">
                        <option value="5" <?php if ($per_page_record == 5) echo "selected" ?>>5</option>
                        <option value="10" <?php if ($per_page_record == 10) echo "selected" ?>>10</option>
                        <option value="30" <?php if ($per_page_record == 30) echo "selected" ?>>30</option>
                        <option value="50" <?php if ($per_page_record == 50) echo "selected" ?>>50</option>
                        <option value="100" <?php if ($per_page_record == 100) echo "selected" ?>>100</option>
                    </select>
                </div>
            </div>
        </form>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" name="selectAll" id="selectAll" onchange="checkSelectAll(<?= $stmt->rowCount(); ?>)"></th>
                    <th>ชื่อปริญญานิพนธ์ (ภาษาไทย)</th>
                    <th>ชื่อปริญญานิพนธ์ (ภาษาอังกฤษ)</th>
                    <th>ปีที่ตีพิมพ์เล่ม</th>
                    <th>ปีการศึกษา</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <?php foreach ($stmt as $row) : ?>
                    <tr>
                        <td><input type="checkbox" name="select_<?= $row['thesis_id'] ?>" class="select"></td>
                        <td><?= $row['thai_name'] ?></td>
                        <td><?= $row['english_name'] ?></td>
                        <td><?= $row['printed_year'] ?></td>
                        <td><?= $row['semester'] . "/" . $row['approval_year'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav class="d-flex justify-content-center">
            <ul class="pagination" id="pagination">
                <?php
                $rs_result = $conn->prepare("SELECT * FROM thesis_document WHERE thesis_status = 1");
                $rs_result->execute();
                $row = $rs_result->fetchAll();
                $total_records = $rs_result->rowCount();

                // Number of pages required.   
                $total_pages = ceil($total_records / $per_page_record);
                $pagLink = "";

                if ($page >= 2) {
                    echo "<li class='page-item'><a class='page-link' href='thesisdelete?page=" . ($page - 1) . "'>  < </a></li>";
                }

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        $pagLink .= "<li class='page-item active'>
                    <a class='page-link' href='thesisdelete?page="
                            . $i . "'>" . $i . " </a>
                    </li>";
                    } else {
                        $pagLink .= "<li class='page-item'>
                    <a class='page-link' href='thesisdelete?page=" . $i . "'>   
                                                " . $i . " </a>
                    </li>";
                    }
                };
                echo $pagLink;

                if ($page < $total_pages) {
                    echo "<li class='page-item'><a class='page-link' href='thesisdelete?page=" . ($page + 1) . "'>  > </a></li>";
                }

                ?>
            </ul>
        </nav>

        <div class="d-flex justify-content-center">
            <button class="btn btn-danger" onclick="submitDelete()">ลบรายการที่เลือก</button>
        </div>
    </div>
    <script>
        function checkSelectAll(count) {
            let selectAll = document.querySelector('#selectAll');
            let selectItems = document.querySelectorAll('.select');
            if (selectAll.checked) {
                selectItems.forEach((item) => {
                    item.checked = true;
                })
            } else {
                selectItems.forEach((item) => {
                    item.checked = false;
                })
            }
        }

        function submitDelete() {
            let anyChecked = false;
            console.log("clicked");
            let selectItems = document.querySelectorAll('.select');
            selectItems.forEach((item) => {
                if (item.checked) {
                    anyChecked = true;
                }
            });
            if (!anyChecked) {
                Swal.fire({
                    text: 'คุณไม่ได้เลือกรายการใด ๆ กรุณาเลือกรายการที่ต้องการจะลบ',
                    icon: 'error',
                    confirmButtonText: 'เข้าใจแล้ว'
                })
            } else {
                let checkedList = [];
                selectItems.forEach(item => {
                    if (item.checked)
                        checkedList.push(item.name);
                })

                Swal.fire({
                    title: "ลบรายการที่เลือกหรือไม่?",
                    text: "รายการที่ลบจะไปอยู่ในถังขยะ",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ลบรายการที่เลือก"
                }).then((result) => {
                    fetch("/FinalProj/thesis_delete.php", {
                        method: "post",
                        body: JSON.stringify(checkedList),
                    }).then(res => {
                        return res.text()
                    }).then(data => {
                        if (data == '1') {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "ลบสำเร็จ!",
                                    icon: "success"
                                }).then(result => {
                                    window.location.replace("/FinalProj/thesisdelete")
                                })
                            }
                        }
                    })
                });
            }
        }

        function showPages(event) {
            selectShow = document.querySelector('#selectShow').value;
            let url = "/FinalProj/thesis_api?search=" + search.value + "&limit=" + selectShow;
            fetch(url, {
                    method: "GET"
                })
                .then(res => {
                    return res.json()
                })
                .then(data => {
                    let html = "";
                    data.forEach(item => {
                        html += `<td><input type='checkbox' name='select_${item.thesis_id}' class='select'></td>`
                        html += `<td>${item.thai_name}</td>`
                        html += `<td>${item.english_name}</td>`
                        html += `<td>${item.printed_year}</td>`
                        html += `<td>${item.semester}/${item.approval_year}</td>`
                        html += "</tr>"
                    })
                    tbody.innerHTML = html;
                    let pagination = document.querySelector('#pagination')
                    let total_records;
                    htmlPagebar = "";
                    page = 1;
                    url = `/FinalProj/thesis_api?search=${search.value}`
                    fetch(url, {
                            method: "GET"
                        }).then(res => {
                            return res.json()
                        })
                        .then(data => {
                            total_records = data.length;
                            let total_pages = Math.ceil(total_records / selectShow)
                            console.log("selectShow ", selectShow)
                            console.log("total_records ", total_records)
                            console.log("total_pages ", total_pages);
                            if (page >= 2) {
                                htmlPagebar += `<li class='page-item'><a class='page-link' href='thesisdelete?page=${page - 1}'> < </a></li>`;
                            }
                            for (let i = 1; i <= total_pages; i++) {
                                if (i == page) {
                                    htmlPagebar += `<li class='page-item active'>
                                <a class='page-link' href='thesisdelete?page=${i}'> ${i}</a>
                                </li>`
                                } else {
                                    htmlPagebar += `<li class='page-item'>
                                <a class='page-link' href='thesisdelete?page=${i}'> ${i}</a>
                                </li>`
                                }
                            }
                            if (page < total_pages) {
                                htmlPagebar += `<li class='page-item'><a class='page-link' href='thesisdelete?page=${page + 1}'> > </a></li>`;
                            }

                            pagination.innerHTML = htmlPagebar;
                        })
                })
        }

        //search
        let search = document.querySelector("#search");
        let tbody = document.querySelector('#tbody');
        let selectShow = document.querySelector("#selectShow").value;
        let page = 1;
        const searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('page')) {
            page = searchParams.get('page')
        } else {
            page = 1;
        }
        search.addEventListener('keyup', () => {
            page = 1;
            let url = "/FinalProj/thesis_api?search=" + search.value + "&limit=" + selectShow;
            fetch(url, {
                    method: "GET"
                })
                .then(res => {
                    return res.json()
                })
                .then(data => {
                    let html = "";
                    data.forEach(item => {
                        html += `<td><input type='checkbox' name='select_${item.thesis_id}' class='select'></td>`
                        html += `<td>${item.thai_name}</td>`
                        html += `<td>${item.english_name}</td>`
                        html += `<td>${item.printed_year}</td>`
                        html += `<td>${item.semester}/${item.approval_year}</td>`
                        html += "</tr>"
                    })
                    tbody.innerHTML = html;
                    let pagination = document.querySelector('#pagination')
                    let total_records;
                    htmlPagebar = "";
                    page = 1;
                    url = `/FinalProj/thesis_api?search=${search.value}`
                    fetch(url, {
                            method: "GET"
                        }).then(res => {
                            return res.json()
                        })
                        .then(data => {
                            total_records = data.length;
                            let total_pages = Math.ceil(total_records / selectShow)
                            console.log("selectShow ", selectShow)
                            console.log("total_records ", total_records)
                            console.log("total_pages ", total_pages);
                            if (page >= 2) {
                                htmlPagebar += `<li class='page-item'><a class='page-link' href='thesisdelete?page=${page - 1}'> < </a></li>`;
                            }
                            for (let i = 1; i <= total_pages; i++) {
                                if (i == page) {
                                    htmlPagebar += `<li class='page-item active'>
                                <a class='page-link' href='thesisdelete?page=${i}'> ${i}</a>
                                </li>`
                                } else {
                                    htmlPagebar += `<li class='page-item'>
                                <a class='page-link' href='thesisdelete?page=${i}'> ${i}</a>
                                </li>`
                                }
                            }
                            if (page < total_pages) {
                                htmlPagebar += `<li class='page-item'><a class='page-link' href='thesisdelete?page=${page + 1}'> > </a></li>`;
                            }

                            pagination.innerHTML = htmlPagebar;
                        })
                })

        })
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>