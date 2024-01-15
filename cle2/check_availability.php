<?php
require_once 'includes/database.php';

/** @var mysqli $db * */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Controleer of het tijdslot beschikbaar is
    $query = "SELECT * FROM calender_apps WHERE (start < '$endTime' AND end > '$startTime')";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        echo 'not available';
    } else {
        echo 'available';
    }

    // sos
    error_log(print_r(mysqli_fetch_all($result, MYSQLI_ASSOC), true));
}

// soss
error_log(print_r($_POST, true));
?>