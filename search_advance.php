<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเล่มปริญญานิพนธ์ - ค้นหาขั้นสูง</title>
    <link rel="icon" type="image/x-icon" href="../img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

</head>

<body>
    <?php require "template/header.php"; ?>

    <div class='container d-flex flex-column my-5 gap-3 position-relative'>
        <form class="d-flex flex-column gap-3" method="POST" id="formSearch">
            <div class="d-flex position-relative">

                <label class="position-absolute" style="top: -1.5rem;">ค้นหาขั้นสูง</label>
                <div class="flex-grow-1 position-relative">
                    <input type="search" name="inputSearch1" id="inputSearch1" class="form-control rounded-start-3 rounded-0" placeholder="คำที่ต้องการค้นหา">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>
                <select name="selectSearch1" id="selectSearch1" class="form-select rounded-end-3 rounded-0 w-25">
                    <option value="all" selected>ทั้งหมด</option>
                    <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                    <option value="keyword">คำสำคัญ</option>
                    <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                    <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                    <option value="abstract">บทคัดย่อ</option>
                    <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>
            </div>

            <div class="d-flex">
                <select name="selectCondition1" id="selectCondition1" class="form-select rounded-start-3 rounded-0 w-25">
                    <option value="AND" selected>AND</option>
                    <option value="OR">OR</option>
                    <option value="AND NOT">NOT</option>
                </select>
                <div class="flex-grow-1">
                    <input type="search" name="inputSearch2" id="inputSearch2" class="form-control rounded-0" placeholder="คำที่ต้องการค้นหา">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>

                <select name="selectSearch2" id="selectSearch2" class="form-select rounded-end-3 rounded-0 w-25">
                    <option value="all" selected>ทั้งหมด</option>
                    <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                    <option value="keyword">คำสำคัญ</option>
                    <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                    <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                    <option value="abstract">บทคัดย่อ</option>
                    <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>
            </div>

            <div class="d-flex">
                <select name="selectCondition2" id="selectCondition2" class="form-select rounded-start-3 rounded-0 w-25">
                    <option value="AND" selected>AND</option>
                    <option value="OR">OR</option>
                    <option value="AND NOT">NOT</option>
                </select>
                <div class="flex-grow-1">
                    <input type="search" name="inputSearch3" id="inputSearch3" class="form-control rounded-0" placeholder="คำที่ต้องการค้นหา">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>

                <select name="selectSearch3" id="" class="form-select rounded-end-3 rounded-0 w-25">
                    <option value="all" selected>ทั้งหมด</option>
                    <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                    <option value="keyword">คำสำคัญ</option>
                    <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                    <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                    <option value="abstract">บทคัดย่อ</option>
                    <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>
            </div>

            <div class="d-flex">
                <select name="selectCondition3" id="selectCondition3" class="form-select rounded-start-3 rounded-0 w-25">
                    <option value="AND" selected>AND</option>
                    <option value="OR">OR</option>
                    <option value="AND NOT">NOT</option>
                </select>
                <div class="flex-grow-1">
                    <input type="search" name="inputSearch4" id="inputSearch4" class="form-control rounded-0" placeholder="คำที่ต้องการค้นหา">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>

                <select name="selectSearch4" id="" class="form-select rounded-end-3 rounded-0 w-25">
                    <option value="all" selected>ทั้งหมด</option>
                    <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                    <option value="keyword">คำสำคัญ</option>
                    <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                    <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                    <option value="abstract">บทคัดย่อ</option>
                    <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>
            </div>

            <div class="d-flex">
                <select name="selectCondition4" id="selectCondition4" class="form-select rounded-start-3 rounded-0 w-25">
                    <option value="AND" selected>AND</option>
                    <option value="OR">OR</option>
                    <option value="AND NOT">NOT</option>
                </select>
                <div class="flex-grow-1">
                    <input type="search" name="inputSearch5" id="inputSearch5" class="form-control rounded-0" placeholder="คำที่ต้องการค้นหา">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>

                <select name="selectSearch5" id="" class="form-select rounded-end-3 rounded-0 w-25">
                    <option value="all" selected>ทั้งหมด</option>
                    <option value="thesis_name">ชื่อปริญญานิพนธ์</option>
                    <option value="keyword">คำสำคัญ</option>
                    <option value="printed_year">ปีตีพิมพ์เล่ม</option>
                    <option value="semester">ภาคการศึกษา/ปี ที่อนุมัติเล่ม</option>
                    <option value="abstract">บทคัดย่อ</option>
                    <option value="author">ชื่อหรือนามสกุลคณะผู้จัดทำ</option>
                    <option value="advisor">ชื่อหรือนามสกุลอาจารย์ที่ปรึกษา</option>
                </select>
            </div>

            <div class="justify-content-center d-flex gap-3">
                <button type="button" id="btnReset" class="btn btn-warning" onclick="reset()">ล้างค่า</button>
                <button class="btn btn-success">ค้นหา</button>
            </div>

        </form>

        <!-- <?php require "thesislist_db.php"  ?> -->
        <!-- ส่วนแสดงรายการ -->
        <div class="container d-flex flex-column my-5 gap-3 position-relative" id="showData">

        </div>
    </div>

    <script>
        //Reset Button
        const btnReset = document.querySelector("#btnReset")
        btnReset.addEventListener('click', () => {
            document.querySelector('#formSearch').reset();
            const showData = document.querySelector('#showData');
            showData.innerHTML = "";
        })

        const form = document.querySelector('#formSearch');
        form.addEventListener('submit', () => {
            const showData = document.querySelector('#showData');
            event.preventDefault();
            const formData = new FormData(form);

            fetch("/FinalProj/search_advance_db.php", {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    return response.text()
                })
                .then(data => showData.innerHTML = data)
        });
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>