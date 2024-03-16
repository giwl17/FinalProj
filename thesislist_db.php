<?php

require "dbconnect.php";

//check page select
if (isset($_POST["page"])) {
    $page  = $_POST["page"];
} else {
    $page = 1;
}
$per_page_record = 10;
$start_from = ($page - 1) * $per_page_record;

if ($_POST['selectSortBy'] == 'sort_printedYear_new') {
    //query thesis
    $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year DESC LIMIT $start_from, $per_page_record";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else if ($_POST['selectSortBy'] == 'sort_printedYear_old') {
    //query thesis
    $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year ASC LIMIT $start_from, $per_page_record";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    //query thesis
    $query = "SELECT * FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1 ORDER BY printed_year DESC LIMIT $start_from, $per_page_record";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//count total record
$query = "SELECT COUNT(*) as count FROM thesis_document WHERE thesis_status = 1 AND approval_status = 1";
$selectAll = $conn->prepare($query);
$selectAll->execute();
$row_count =  $selectAll->fetch();
$all_result =  $row_count['count'];
?>

<form method="POST" action="" id="formSort">
    <div class='row align-items-center justify-content-end gap-1'>
        <label class='w-auto' for='sortBy'>เรียงโดย</label>
        <select class='form-select w-auto' id='selectSortBy' name='selectSortBy' onchange="document.getElementById('formSort').submit();">
            <option value="sort_printedYear_new" <?php if ($_POST['selectSortBy'] == 'sort_printedYear_new') echo "selected"; ?>>ปีที่ตีพิมพ์เล่ม ใหม่->เก่า</option>
            <option value="sort_printedYear_old" <?php if ($_POST['selectSortBy'] == 'sort_printedYear_old') echo "selected"; ?>>ปีที่ตีพิมพ์เล่ม เก่า->ใหม่</option>
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
                    <?php if ($row->prefix_coAdvisor != '') : ?>
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

<!-- menu page -->
<?php
$total_pages = ceil($all_result / $per_page_record);
$pagLink = "";
?>


<nav class='d-flex justify-content-center'>
    <ul class='pagination d-flex flex-wrap' id='pagination'>

        <?php if ($page >= 2) : ?>
            <form action="" method="POST">
                <li class='page-item'>
                    <input type="hidden" name="page" value="<?= ($page - 1) ?>">
                    <button class='page-link rounded-0 rounded-start-3 '> < </button>
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