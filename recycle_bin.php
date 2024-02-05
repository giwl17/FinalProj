<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Check All Data</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JavaScript -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


</head>

<body>
    <?php require "template/header.php"; ?>

    <div class="container mt-5">
        <h2>Check All Data</h2>

        <form id="checkboxForm" action="bin_db.php" method="post">
            <table id="dataTable" class="display">
                <thead>
                    <tr>
                        <th>Select All</th>
                        <th>Thesis ID</th>
                        <th>Thai Name</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Fetch Data from Database -->
                    <?php
                    // Include your database connection code here
                    include 'dbconnect.php';

                    // Fetch data from the database
                    $stmt = $conn->query("SELECT * FROM thesis_document");
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Display checkboxes for each record
                    foreach ($data as $row) {
                        echo '<tr>';
                        echo '<td><input type="checkbox" class="form-check-input" name="checkbox[]" value="' . $row['thesis_id'] . '"></td>';
                        echo '<td>' . $row['thesis_id'] . '</td>';
                        echo '<td>' . $row['thai_name'] . '</td>';
                        echo '</tr>';
                    }
                    ?>

                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
        </script>

</body>

</html>