<?php require "dbconnect.php"; ?>
<?php if ($_SESSION['role'] == 3) : ?>
    <?php
    $currentUser = $_SESSION['prefix'] . $_SESSION['name'] . " " . $_SESSION['lastname'];
    $select = $conn->prepare("SELECT thesis_id, thai_name, english_name, dateTime_import, import_by FROM thesis_document WHERE approval_status = 0 AND import_by = :currentUser");
    $select->bindParam(":currentUser", $currentUser);
    $select->execute();
    $result = $select->fetchAll();
    ?>
<?php else : ?>
    <?php
    $select = $conn->prepare("SELECT thesis_id, thai_name, english_name, dateTime_import, import_by FROM thesis_document WHERE approval_status = 0");
    $select->execute();
    $result = $select->fetchAll();
    ?>
<?php endif; ?>

<?php if ($select->rowCount() > 0) : ?>
    <table class='table'>
        <thead>
            <tr>
                <th scope='col'>รายการที่</th>
                <th scope='col'>ชื่อภาษาไทย</th>
                <th scope='col'>ชื่อภาษาอังกฤษ</th>
                <th scope='col'>วันที่เพิ่มข้อมูล</th>
                <th scope='col'>เพิ่มข้อมูลโดย</th>
                <th scope='col'>ดำเนินการ</th>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <th scope='row'><?= $i ?></th>
                    <td scope='row'><?= $row['thai_name'] ?></td>
                    <td scope='row'><?= $row['english_name'] ?></td>
                    <td scope='row'><?= $row['dateTime_import'] ?></td>
                    <td scope='row'><?= $row['import_by'] ?></td>
                    <?php if ($_SESSION['role'] == 3) : ?>
                        <!-- แก้ไข -->
                        <td scope='row'><a class='btn btn-warning' href='thesislistwaiting/thesis?id=<?= $row['thesis_id'] ?>'>แก้ไข</a></td>
                    <?php else : ?>
                        <!-- ตรวจสอบ -->
                        <td scope='row'><a class='btn btn-warning' href='thesislistwaiting/thesis?id=<?= $row['thesis_id'] ?>'>ตรวจสอบ</a></td>
                    <?php endif; ?>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>

        </tbody>
    </table>
<?php else : ?>
    <p>ไม่มีรายการที่รอตรวจสอบ</p>

<?php endif; ?>