<?php
require_once 'includes/database.php';

/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Check begin en Eind tijd van tijdslot beschikbaarheidd
    $query = "SELECT start, end FROM calender_apps WHERE id = '$id'";
    $result = mysqli_query($db, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $start = $row['start'];
        $end = $row['end'];

        // Verwijder tijdslot ALS dus
        $deleteQuery = "DELETE FROM calender_apps WHERE id = '$id'";
        $deleteResult = mysqli_query($db, $deleteQuery);

        if ($deleteResult) {
            echo "Success";
            // LAAT TIJDEN ZIEN VOOR USER
            echo ',' . $start . ',' . $end;
        } else {
            echo "Error";
        }
    } else {
        echo "Error fetching appointment details";
    }
}

// OMFG DIT IS EEN STRUGGLE
error_log(print_r($_POST, true));
?>