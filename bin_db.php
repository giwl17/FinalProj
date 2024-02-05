<?php
// Include your database connection code here
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the submitted checkboxes
    if (isset($_POST['checkbox'])) {
        $checkboxValues = $_POST['checkbox'];

        // Perform your database operations here using $pdo
        // For example, you can loop through $checkboxValues and perform actions
        foreach ($checkboxValues as $value) {
            // Perform database-related operations based on the selected checkboxes
            // Example: $pdo->prepare("UPDATE your_table SET column_name = 'value' WHERE id = :id")->execute([':id' => $value]);
        }
    }

    // Redirect back to the main page or perform other actions as needed
    header('Location: index.php');
    exit();
}
?>
<?php
// Include your database connection code here
include 'dbconnect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the submitted checkboxes
    if (isset($_POST['checkbox'])) {
        $checkboxValues = $_POST['checkbox'];

        // Perform your database operations here using $pdo
        // For example, you can loop through $checkboxValues and perform actions
        foreach ($checkboxValues as $value) {
            // Perform database-related operations based on the selected checkboxes
            // Example: $pdo->prepare("UPDATE your_table SET column_name = 'value' WHERE id = :id")->execute([':id' => $value]);
        }
    }

    // Redirect back to the main page or perform other actions as needed
    header('Location: recycle_bin.php');
    exit();
}
?>
