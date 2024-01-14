<?php
require_once 'includes/database.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Fetch the start and end time of the appointment before deletion
    $query = "SELECT start, end FROM calender_apps WHERE id = '$id'";
    $result = mysqli_query($db, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $start = $row['start'];
        $end = $row['end'];

        // Verwijder de afspraak
        $deleteQuery = "DELETE FROM calender_apps WHERE id = '$id'";
        $deleteResult = mysqli_query($db, $deleteQuery);

        if ($deleteResult) {
            echo "Success";
            // Return the start and end time for the availability check
            echo ',' . $start . ',' . $end;
        } else {
            echo "Error";
        }
    } else {
        echo "Error fetching appointment details";
    }
}

// Log POST data
error_log(print_r($_POST, true));
?>