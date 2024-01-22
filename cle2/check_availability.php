<?php
require_once 'includes/database.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Extract de datum uit de starttijd
    $date = date('Y-m-d', strtotime($startTime));

    // Controleer of er al een reservering is voor het opgegeven tijdslot op de specifieke dag
    $query = "SELECT id, title, start, end FROM calender_apps WHERE (start < '$endTime' AND end > '$startTime')";

    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        echo 'not available';
    } else {
        // Controleer ook of er al een reservering is voor het andere tijdslot op dezelfde dag
        $otherTimeSlot = ($endTime == '2024-01-10T02:00:00+01:00') ? '1' : '2';
        $otherStartTime = "$date 09:00:00";
        $otherEndTime = "$date 11:00:00";

        $queryOther = "SELECT id, title, start, end FROM calender_apps WHERE (start < '$endTime' AND end > '$startTime')";

        $resultOther = mysqli_query($db, $queryOther);

        if (mysqli_num_rows($resultOther) > 0) {
            echo 'not available';
        } else {
            echo 'available';
        }
    }

    // sos
    error_log(print_r(mysqli_fetch_all($result, MYSQLI_ASSOC), true));
}

// soss
error_log(print_r($_POST, true));
?>
