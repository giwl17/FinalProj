<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มเล่มปริญญานิพนธ์</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require "template/header.php"; ?>
    <form class="container mt-4" method="post" action="thesis_add_db.php">
        <h1 class="h3 text-primary text-center mb-4">เพิ่มข้อมูลปริญญานิพนธ์</h1>
        <div class="form-group mb-3">
            <label for="thesis_name_th">ชื่อปริญญานิพนธ์ (ภาษาไทย)</label>
            <textarea class="form-control" name="thesis_name_th" id="" cols="30" rows="4" style="resize: none;"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="thesis_name_en">ชื่อปริญญานิพนธ์ (ภาษาอังกฤษ)</label>
            <textarea class="form-control" name="thesis_name_en" id="" cols="30" rows="4" style="resize: none;"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="abstract">บทคัดย่อ</label>
            <textarea class="form-control" name="abstract" id="" cols="30" rows="10" style="resize: none;"></textarea>
        </div>

        <div>
            <p>คณะผู้จัดทำ</p>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="member1" id="">
                <label class="form-check-label" for="member1">สมาชิกคนที่ 1</label>
                <div>
                    <div class="form-group mb-3">
                        <label for="member1_id">รหัสนักศึกษา</label>
                        <input class="form-control" type="text" name="member1_id" id="">
                    </div>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                        <div class="col form-group">
                            <label for="member1_prefix">คำนำหน้า</label>
                            <select class="form-select" name="member1_prefix" id="">
                                <option value=""></option>
                                <option value="นาย">นาย</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="นาง">นาง</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="">ชื่อ</label>
                            <input class="form-control" type="text" name="member1_firstname" id="">
                        </div>
                        <div class="col form-group">
                            <label for="">นามสกุล</label>
                            <input class="form-control" type="text" name="member1_lastname" id="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="member2" id="">
                <label class="form-check-label" for="member2">สมาชิกคนที่ 2</label>
                <div>
                    <div class="form-group mb-3">
                        <label for="member2_id">รหัสนักศึกษา</label>
                        <input class="form-control" type="text" name="member2_id" id="">
                    </div>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                        <div class="col form-group">
                            <label for="member2_prefix">คำนำหน้า</label>
                            <select class="form-select" name="member2_prefix" id="">
                                <option value=""></option>
                                <option value="นาย">นาย</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="นาง">นาง</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="member2_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="member2_firstname" id="">
                        </div>
                        <div class="col form-group">
                            <label for="member2_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="member2_lastname" id="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="member3" id="">
                <label class="form-check-label" for="member3">สมาชิกคนที่ 3</label>
                <div>
                    <div class="form-group mb-3">
                        <label for="member3_id">รหัสนักศึกษา</label>
                        <input class="form-control" type="text" name="member3_id" id="">
                    </div>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                        <div class="col form-group">
                            <label for="member3_prefix">คำนำหน้า</label>
                            <select class="form-select" name="member3_prefix" id="">
                                <option value=""></option>
                                <option value="นาย">นาย</option>
                                <option value="นางสาว">นางสาว</option>
                                <option value="นาง">นาง</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="member3_firstname">ชื่อ</label>
                            <input class="form-control" type="text" name="member3_firstname" id="">
                        </div>
                        <div class="col form-group">
                            <label for="member3_lastname">นามสกุล</label>
                            <input class="form-control" type="text" name="member3_lastname" id="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="advisor">อาจารย์ที่ปรึกษาหลัก</label>
            <select class="form-select" name="advisor" id="">
                <option value=""></option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label class="" for="coAdvisor">อาจารย์ที่ปรึกษาร่วม</label>
            <select class="form-select" name="coAdvisor" id="">
                <option value=""></option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label class="" for="chairman">ประธานกรรมการ</label>
            <select class="form-select" name="chairman" id="">
                <option value=""></option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label class="" for="director1">กรรมการคนที่ 1</label>
            <select class="form-select" name="director1" id="">
                <option value=""></option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label class="" for="director2">กรรมการคนที่ 2</label>
            <select class="form-select" name="director2" id="">
                <option value=""></option>
            </select>
        </div>

        <div class="row align-items-center mb-3">
            <div class="col-auto">
                <div class="col-form-label">ปีการศึกษาที่ทำเสร็จ</div>
            </div>
            <div class="col-auto">
                <select name="" id="" class="form-select">
                    <option value=""></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="col-auto">
                <div class="col-form-label">/</div>
            </div>
            <div class="col-auto">
                <input class="form-control" type="number" name="" id="" maxlength="4">
            </div>

        </div>

        <div class="form-group" id="formWord">
            <label for="">คำสำคัญ (อย่างน้อย 3 คำ)</label>
            <input class="form-control mb-3" type="text" name="" id="word_1" placeholder="คำสำคัญ 1">
            <input class="form-control mb-3" type="text" name="" id="word_2" placeholder="คำสำคัญ 2">
            <input class="form-control mb-3" type="text" name="" id="word_3" placeholder="คำสำคัญ 3">
        </div>
        <div class="mb-3">
            <input class="btn btn-success" type="button" value="+" id="buttonAddWord">
            <input class="btn btn-danger" type="button" value="-" id="buttonDeleteWord">
        </div>

        <div class="mb-3">
            <label for="" class="form-label">ไฟล์หน้าอนุมัติ (ชนิดไฟล์ PDF)</label>
            <input class="form-control" type="file" id="">
        </div>

        <div class="mb-3">
            <label for="" class="form-label">ไฟล์เล่มปริญญานิพนธ์ (ชนิดไฟล์ PDF)</label>
            <input class="form-control" type="file" id="">
        </div>

        <div class="mb-4">
            <label for="" class="form-label">ไฟล์โปสเตอร์ (ชนิดไฟล์ PDF)</label>
            <input class="form-control" type="file" id="">
        </div>
        <input class="btn btn-primary container-fluid mb-4" type="submit" value="เพิ่มข้อมูล">
    </form>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        let buttonAddWord = document.getElementById('buttonAddWord');
        let buttonDeleteWord = document.getElementById('buttonDeleteWord');
        let formWord = document.getElementById('formWord');
        let i = 4;
        buttonAddWord.addEventListener('click', () => {
            $html = ' <input class="form-control mb-3" type="text" name="" id="word_' + i + '" placeholder="คำสำคัญ ' + i + '">';
            formWord.insertAdjacentHTML('beforeend', $html);
            i++;
            console.log('i=', i);
        })
        buttonDeleteWord.addEventListener('click', () => {
            console.log('click');
            if (i > 4) {
                let id = 'word_' + (i - 1);
                console.log(id);
                console.log(document.querySelector(id));
                let currentDelete = document.getElementById(id);
                currentDelete.remove();
                i--;
            }
        })
    </script>
</body>

</html>