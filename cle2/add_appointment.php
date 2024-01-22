<?php
require_once 'includes/database.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start'];
    $end = $_POST['end'];
    $timeSlot = $_POST['timeSlot'];

    // Voeg debug-opdrukken toe om de waarden te bekijken
    error_log("Received timeSlot: $timeSlot");
    error_log("Received start: $start");
    error_log("Received end: $end");

    // Bepaal de juiste tijdslotnaam op basis van het tijdslotnummer
    $title = ($timeSlot === '1') ? 'Tijdslot 1 (09:00 - 11:00)' : 'Tijdslot 2 (13:30 - 15:30)';

    // Voeg debug-opdrukken toe om de waarden te bekijken
    error_log("Generated title: $title");

    // Voeg de reservering toe
    $query = "INSERT INTO calender_apps (start, end, title, is_reserved_slot1 , is_reserved_slot2) VALUES ('$start', '$end', '$title', 1, 2)";
    $result = mysqli_query($db, $query);

    if ($result) {
        echo mysqli_insert_id($db);
    } else {
        echo "Error";
    }
}

// help
error_log(print_r($_POST, true));
?>
