<?php
/** @var mysqli $db */
require_once 'includes/database.php';

$dayId = 1;

$query = "SELECT COUNT(DISTINCT timeslot_id) AS timeslot_count FROM appointments WHERE day_id = $dayId";

$result = mysqli_query($db, $query);

if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Access the timeslot count using the column alias
        $timeslotCount = $row['timeslot_count'];

        // Output the result
        echo "Timeslot count for day_id $dayId: $timeslotCount";
    } else {
        echo "No result found for day_id $dayId";
    }
} else {
    // Handle the query error
    echo "Error executing query: " . mysqli_error($db);
}

// Close the database connection
mysqli_close($db);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
</body>
</html>
