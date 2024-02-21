<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเล่มปริญญานิพนธ์ - เพิ่มข้อมูลนักศึกษา</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/js/bootstrap.min.js">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
    <?php require "template/header.php"; ?>
    <br>
    <div class="container">
        <h1 class="h4 text-primary text-center mb-4">เพิ่มข้อมูลนักศึกษา</h1>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">กรอกข้อมูล</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">อัปโหลดไฟล์ CSV</button>
            </li>

        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- tab manual -->


            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <form class="container mt-4" method="post" action="sendMailCreateAccount" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="prefix" class="form-label">คำนำหน้า</label>
                            <select class="form-select" id="prefix" name="prefix" required>
                                <option value="">กรุณาเลือกคำนำหน้า</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="other">อื่นๆ(ยังไม่ทำ)</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="name" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="กรุณาใส่ชื่อ" required>
                        </div>
                        <div class="col-md-5">
                            <label for="lastname" class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="กรุณาใส่นามสกุล" required>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="studentID" class="form-label">รหัสนักศึกษา</label>
                            <input type="text" class="form-control" id="studentID" name="studentID" placeholder="กรุณาใส่รหัสนักศึกษา (มีขีด)" maxlength="14" pattern="\d{12}-\d$" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">อีเมล์</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="กรุณาใส่อีเมล์" required>
                        </div>
                    </div>
                    <div hidden>
                        <input type="text" id="role" name="role" value="5">
                        <input type="text" id="download_permissions" name="download_permissions" value="0">
                        <input type="text" id="member_manage_permission" name="member_manage_permission" value="0">
                        <input type="text" id="account_manage_permission" name="account_manage_permission" value="0">
                        <input type="text" id="status" name="status" value="1">
                        <input type="text" id="page" name="page" value="student_add">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">ส่งอีเมล์เพื่อสร้างรหัสผ่าน</button>
                </form>
            </div>


            <!-- tab csv -->
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <div class="container mt-5">
                    <form action="csv_reader.php" method="post" enctype="multipart/form-data" class="form-inline">
                        <div class="form-group">
                            <label for="csvFile" class="mr-2">Choose a CSV file:</label>
                            <input type="file" id="csvFile" name="csvFile" class="form-control-file" accept=".csv">
                        </div>

                        <button type="submit" class="btn btn-primary ml-2">Upload</button>
                    </form>
                </div>



            </div>

        </div>
        <br>
    </div>
    
    <script>
        document.getElementById('csvFile').addEventListener('change', handleFile);

        function handleFile(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const csvData = e.target.result;
                    displayTable(csvData);
                };

                reader.readAsText(file);
            }
        }

        function displayTable(csvData) {
            const rows = csvData.split('\n');
            const tableContainer = document.getElementById('tableContainer');

            let tableHTML = '<table>';
            let count = 0;
            rows.forEach(row => {

                const columns = row.split(',');
                tableHTML += '<tr>';
                columns.forEach(column => {
                    if (count == 0) {
                        if (column != "")
                            tableHTML += `<th>${column}</th>`;
                    } else {
                        if (column != "")
                            tableHTML += `<td>${column}</td>`;
                    }
                });
                tableHTML += '</tr>';
                count++;
            });

            tableHTML += '</table>';
            tableContainer.innerHTML = tableHTML;
        }
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>