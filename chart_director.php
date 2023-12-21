<?php
include "dbconnect.php";
$select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document GROUP BY prefix_advisor, name_advisor, surname_advisor");
$prefix = "ผู้ช่วยศาสตราจารย์";
$name = "มาโนช";
$surname = "ประชา";
$select->bindParam(":prefix", $prefix);
$select->execute();
$result_manod = $select->fetchAll(PDO::FETCH_ASSOC);

// $dataPoints = array();

// print_r($result_manod);
foreach ($result_manod as $row) {
    $dataPoints[] =  array("y" => $row['count'], "label" => $row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor']);
}

// var_dump($dataPoints);

// $dataPoints = array(
//     array("y" => $result_manod['number'], "label" => "ผู้ช่วยศาสตราจารย์ มาโนช ประชา"),
//     array("y" => 12, "label" => "ผู้ช่วยศาสตราจารย์ ดร.ศิริชัย เตรียมล้ำเลิศ"),
//     array("y" => 28, "label" => "ผู้ช่วยศาสตราจารย์ นชิรัตน์ ราชบุรี"),
//     array("y" => 18, "label" => "ผู้ช่วยศาสตราจารย์ สมรรถชัย จันทรัตน์"),
//     array("y" => 18, "label" => "ดร.ปอริน กองสุวรรณ"),
//     array("y" => 41, "label" => "ผู้ช่วยศาสตราจารย์ เดชรัชต์ ใจถวิล")
// );
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถิติการกำกับเล่ม</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.stock.min.js"></script>
    <script>
        function compareDataPointYAscend(dataPoint1, dataPoint2) {
            return dataPoint1.y - dataPoint2.y;
        }

        function compareDataPointYDescend(dataPoint1, dataPoint2) {
            return dataPoint2.y - dataPoint1.y;
        }


        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "สถิติกำกับปริญญานิพนธ์"
                },
                axisY: {
                    title: "จำนวนปริญญานิพนธ์",
                    includeZero: true,
                },
                data: [{
                    click: onClick,
                    type: "bar",
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontWeight: "bolder",
                    indexLabelFontColor: "white",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.options.data[0].dataPoints.sort(compareDataPointYAscend);
            chart.render();

            function onClick(e) {
                // alert(e.dataSeries.type + ", dataPoint { label:" + e.dataPoint.label + ", y: " + e.dataPoint.y + " }");
                location.href = `search?advisor=${e.dataPoint.label}`;
            }
        }
    </script>
</head>

<body>
    <?php require_once('./template/header.php') ?>
    <div class="container my-4">
        <div id="chartContainer" class="w-100" style="height: 370px;"></div>
    </div>

    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>