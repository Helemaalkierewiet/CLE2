<?php

/**@var array $appointments
 * @var mysqli $db
 */

require_once 'includes/database.php';

$query ="SELECT *
FROM appointments
JOIN users ON appointments.user_id = users.id
JOIN timeslots ON appointments.timeslot_id = timeslots.id
JOIN days ON appointments.day_id = days.id";

$stmt = mysqli_prepare($db, $query);

mysqli_stmt_execute($stmt);

$appointmentTable = mysqli_stmt_get_result($stmt) or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$appointments = [];

while ($row = mysqli_fetch_assoc($appointmentTable)) {
    $appointments[] = $row;
}

mysqli_close($db);

$index = (isset($_GET['id']) && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'] - 1) : null;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details <?= htmlspecialchars($appointments[$index]['title']) ?> | afspraak</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2><?= isset($appointments[$index]['title']) ? htmlspecialchars($appointments[$index]['title']) : 'Appointment Not Found' ?> details</h2>
        <section>
            <ul>
                <li>Voornaam: <?= isset($appointments[$index]['first_name']) ? htmlspecialchars($appointments[$index]['first_name']) : '' ?></li>
                <li>Achternaam: <?= isset($appointments[$index]['last_name']) ? htmlspecialchars($appointments[$index]['last_name']) : '' ?></li>
                <li>Datum: <?= isset($appointments[$index]['date']) ? htmlspecialchars($appointments[$index]['date']) : '' ?></li>
                <li>Dag: <?= isset($appointments[$index]['name']) ? htmlspecialchars($appointments[$index]['name']) : '' ?></li>
                <li>Maand: <?= isset($appointments[$index]['month']) ? htmlspecialchars($appointments[$index]['month']) : '' ?></li>
                <li>Jaar: <?= isset($appointments[$index]['year']) ? htmlspecialchars($appointments[$index]['year']) : '' ?></li>
                <li>Tijdslot: <?= isset($appointments[$index]['timeslot_id']) ? htmlspecialchars($appointments[$index]['timeslot_id']) : '' ?></li>
                <li>Start tijd: <?= isset($appointments[$index]['start_time']) ? htmlspecialchars($appointments[$index]['start_time']) : '' ?></li>
                <li>Eind tijd: <?= isset($appointments[$index]['end_time']) ? htmlspecialchars($appointments[$index]['end_time']) : '' ?></li>
                <li>Beschrijving: <?= isset($appointments[$index]['description']) ? htmlspecialchars($appointments[$index]['description']) : '' ?></li>
            </ul>
        </section>

    </div>
</body>
</html>
