<?php
require_once 'includes/database.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start'];
    $end = $_POST['end'];

    $query = "INSERT INTO calender_apps (start, end) VALUES ('$start', '$end')";
    $result = mysqli_query($db, $query);

    if ($result) {
        echo mysqli_insert_id($db);
    } else {
        echo "Error";
    }

    // debuggr
    error_log(print_r($result, true));
}

// help
error_log(print_r($_POST, true));
?>