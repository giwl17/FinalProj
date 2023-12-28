<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเล่มปริญญานิพนธ์ - รายการ</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

</head>

<body>
    <?php require "template/header.php"; ?>

    <div class='container d-flex flex-column my-5 gap-3 position-relative'>
        <div class="d-flex flex-column">
            <div class="d-flex position-relative">
                <label class="position-absolute" style="top: -1.5rem;">ค้นหารายการจาก</label>
                <select name="" id="selectSearch" class="form-select rounded-0 w-25">
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
                    <input type="search" name="" id="inputSearch" class="form-control rounded-0" placeholder="">
                    <div class="w-100 position-absolute d-none" id="searching">
                    </div>
                </div>
                <button class="btn btn-outline-secondary rounded-0 col-auto" onclick="submitSearch();"><i class="bi bi-search px-1"></i>ค้นหา</button>
            </div>
            <a href='/FinalProj/search/advance' class="text-end mt-2 link-dark">การค้นหาขัั้นสูง</a>
        </div>

        <?php require "thesislist_db.php"  ?>
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
    </script>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>