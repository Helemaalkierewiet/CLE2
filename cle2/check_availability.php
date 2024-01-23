<?php
require_once 'includes/database.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Extract the date from the start time
    $date = date('Y-m-d', strtotime($startTime));

    // Define the time slots
    $timeSlot1 = '1';
    $timeSlot2 = '2';

    // Check if the specified time slot is reserved
    $query = "SELECT is_reserved_slot FROM calender_apps WHERE (start < '$endTime' AND end > '$startTime')";

    $result = mysqli_query($db, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $isReservedSlot = $row['is_reserved_slot'];
        if ($isReservedSlot == '1') {
            echo 'not available';
            exit; // Stop further processing if the time slot is not available
        }
    }

    // Check if the other time slot is reserved
    $otherTimeSlot = ($endTime == '2024-01-10T02:00:00+01:00') ? $timeSlot1 : $timeSlot2;
    $queryOther = "SELECT is_reserved_slot FROM calender_apps WHERE (start < '$endTime' AND end > '$startTime')";

    $resultOther = mysqli_query($db, $queryOther);

    if ($rowOther = mysqli_fetch_assoc($resultOther)) {
        $isReservedSlotOther = $rowOther['is_reserved_slot'];
        if ($isReservedSlotOther == '1') {
            echo 'not available';
            exit; // Stop further processing if the other time slot is not available
        }
    }

    echo 'available';

    // For debugging purposes, you can log the database results and POST data
    error_log("Result for specified time slot: " . print_r($row, true));
    error_log("Result for other time slot: " . print_r($rowOther, true));
    error_log("POST data: " . print_r($_POST, true));
}
?>
