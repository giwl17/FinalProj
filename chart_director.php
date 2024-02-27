<?php
include "dbconnect.php";
$select = $conn->prepare("SELECT prefix_advisor, name_advisor, surname_advisor, COUNT(*) as count FROM thesis_document GROUP BY prefix_advisor, name_advisor, surname_advisor");
$select->execute();
$result = $select->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $dataPoints[] =  array("y" => $row['count'], "label" => $row['prefix_advisor'] . " " . $row['name_advisor'] . " " . $row['surname_advisor']);
}

//get max year and min year
$stmt = $conn->prepare("SELECT MAX(approval_year) AS max FROM thesis_document");
$result = $stmt->execute();
if ($result) {
    $year = $stmt->fetch();
    $maxYear = $year['max'];
}
$stmt = $conn->prepare("SELECT MIN(approval_year) AS min FROM thesis_document");
$result = $stmt->execute();
if ($result) {
    $year = $stmt->fetch();
    $minYear = $year['min'];
}

//get count collect thesis
$stmt = $conn->prepare("SELECT COUNT(approval_year) as count, approval_year FROM thesis_document  WHERE approval_status = 1 GROUP BY approval_year");
$result = $stmt->execute();
$dataPoints_count = array();
if ($result) {
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        $count_thesis = $row['count'];
        $approval_year = $row['approval_year'];
        $dataPoints_count[] = array("x" => $approval_year, "y" => $count_thesis);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถิติข้อมูล</title>
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
                axisY: {
                    title: "จำนวนปริญญานิพนธ์",
                    includeZero: true,
                    interval: 1,
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

            var chart_count = new CanvasJS.Chart("chartContainer_count", {
                animationEnabled: true,
                axisY: {
                    title: "จำนวน",
                    interval: 1,
                    includeZero: true,
                },
                axisX: {
                    title: "ปีการศึกษา",
                    interval: 1,
                    includeZero: true,
                },
                data: [{
                    type: "column",
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontWeight: "bolder",
                    indexLabelFontColor: "white",
                    dataPoints: <?php echo json_encode($dataPoints_count, JSON_NUMERIC_CHECK); ?>
                }]
            })
            chart_count.render();

            function onClick(e) {
                // alert(e.dataSeries.type + ", dataPoint { label:" + e.dataPoint.label + ", y: " + e.dataPoint.y + " }");
                location.href = `search?advisor=${e.dataPoint.label}`;
            }
        }
    </script>
</head>

<body>
    <?php require_once('./template/header.php') ?>
    <div class="container my-4 d-flex flex-column gap-3">
        <div class="d-flex gap-2 align-items-end">
            <div class="form-group col-2">
                <label class="">ปีการศึกษา</label>
                <select class="form-select" id="selectYear">
                    <option value="all">ทั้งหมด</option>
                    <?php
                    if ($minYear === $maxYear) {
                        echo "
                         <option value='$maxYear'>$maxYear</option>
                       ";
                    } else {
                        for ($i = $maxYear; $i >= $minYear; $i--) {
                            echo "<option>$i</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="ButtonReset()">รีเซต</button>
                <button class="btn btn-primary" onclick="ButtonSubmit()">ค้นหา</button>
            </div>
        </div>
        <h1 class="h3 text-center">สถิติการจัดเก็บเล่มปริญญานิพนธ์</h1>
        <div id="chartContainer_count" class="w-100" style="height: 370px;"></div>
        <h1 class="h3 text-center">สถิติกำกับปริญญานิพนธ์</h1>
        <div id="chartContainer" class="w-100" style="height: 370px;"></div>
    </div>

    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        function ButtonReset() {
            document.getElementById("selectYear").value = "all";

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                axisY: {
                    title: "จำนวนปริญญานิพนธ์",
                    includeZero: true,
                    interval: 1,
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

            var chart_count = new CanvasJS.Chart("chartContainer_count", {
                animationEnabled: true,
                axisY: {
                    title: "จำนวน",
                    interval: 1,
                    includeZero: true,
                },
                axisX: {
                    title: "ปีการศึกษา",
                    interval: 1,
                    includeZero: true,
                },
                data: [{
                    type: "column",
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontWeight: "bolder",
                    indexLabelFontColor: "white",
                    dataPoints: <?php echo json_encode($dataPoints_count, JSON_NUMERIC_CHECK); ?>
                }]
            })
            chart_count.render();
        }

        function ButtonSubmit() {
            let year = document.getElementById("selectYear").value;
            let url = `chart_director_db.php?y=${year}`;
            fetch(url, {
                    method: "GET"
                })
                .then(res => {
                    return res.json()
                })
                .then(data => {
                    console.log(data)
                    data1 = []
                    data2 = []
                    data.forEach(element => {
                        // console.log(element.data2)
                        if (element.data1 !== undefined) {
                            data1.push({
                                label: element.data1.label,
                                y: element.data1.y
                            })
                        }
                        if (element.data2 !== undefined) {
                            data2.push({
                                x: element.data2.x,
                                y: element.data2.y
                            })
                        }
                    });
                    var chart = new CanvasJS.Chart("chartContainer", {
                        animationEnabled: true,
                        axisY: {
                            title: "จำนวนปริญญานิพนธ์",
                            includeZero: true,
                            interval: 1,
                        },
                        data: [{
                            click: onClick,
                            type: "bar",
                            indexLabel: "{y}",
                            indexLabelPlacement: "inside",
                            indexLabelFontWeight: "bolder",
                            indexLabelFontColor: "white",
                            dataPoints: data1
                        }]
                    });
                    chart.options.data[0].dataPoints.sort(compareDataPointYAscend);
                    chart.render();

                    var chart_count = new CanvasJS.Chart("chartContainer_count", {
                        animationEnabled: true,
                        axisY: {
                            title: "จำนวน",
                            interval: 1,
                            includeZero: true,
                        },
                        axisX: {
                            title: "ปีการศึกษา",
                            interval: 1,
                            includeZero: true,
                        },
                        data: [{
                            type: "column",
                            indexLabel: "{y}",
                            indexLabelPlacement: "inside",
                            indexLabelFontWeight: "bolder",
                            indexLabelFontColor: "white",
                            dataPoints: data2
                        }]
                    })
                    chart_count.render();
                })
                .catch(error => console.error(error))
        }

        function onClick(e) {
            location.href = `search?advisor=${e.dataPoint.label}`;
        }
    </script>
</body>

</html>