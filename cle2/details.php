<?php
/** @var array $appointments
 * @var mysqli $db
 */

require_once 'includes/database.php';

$date = (isset($_GET['date']) && is_numeric($_GET['date'])) ? htmlspecialchars($_GET['date']) : null;

$query = "SELECT *
          FROM appointments
          JOIN users ON appointments.user_id = users.id
          JOIN timeslots ON appointments.timeslot_id = timeslots.id
          JOIN days ON appointments.day_id = days.id
          WHERE day_id = ?";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $date);
mysqli_stmt_execute($stmt);

$appointmentTable = mysqli_stmt_get_result($stmt) or die('Error ' . mysqli_error($db) . ' with query ' . $query);

$appointments = [];

while ($row = mysqli_fetch_assoc($appointmentTable)) {
    $appointments[] = $row;
}

$dayNameQuery = "SELECT name FROM days WHERE id = ?";

$dayNameStmt = mysqli_prepare($db, $dayNameQuery);
mysqli_stmt_bind_param($dayNameStmt, "i", $date);
mysqli_stmt_execute($dayNameStmt);

$dayNameResult = mysqli_stmt_get_result($dayNameStmt);
$dayNameRow = mysqli_fetch_assoc($dayNameResult);
$dayName = $dayNameRow ? $dayNameRow['name'] : 'Unknown';

mysqli_close($db);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Afspraken op <?= htmlspecialchars($dayName) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="details-container">
    <header class="details-header">
        <h2>Afspraken op <?= htmlspecialchars($dayName) ?></h2>

        <?php if (empty($appointments)): ?>
            <p>Geen afspraken op deze datum</p>
        <?php else: ?>
    </header>
    <section class="details-content">
     <?php foreach ($appointments as $appointment): ?>
        <section class="details-list">
            <h3><?= isset($appointment['title']) ? htmlspecialchars($appointment['title']) : 'Appointment Not Found' ?> details</h3>
            <ul>
                <li>Voornaam: <?= isset($appointment['first_name']) ? htmlspecialchars($appointment['first_name']) : '' ?></li>
                <li>Achternaam: <?= isset($appointment['last_name']) ? htmlspecialchars($appointment['last_name']) : '' ?></li>
                <li>Tijdslot: <?= isset($appointment['timeslot_id']) ? htmlspecialchars($appointment['timeslot_id']) : '' ?></li>
                <li>Start tijd: <?= isset($appointment['start_time']) ? htmlspecialchars($appointment['start_time']) : '' ?></li>
                <li>Eind tijd: <?= isset($appointment['end_time']) ? htmlspecialchars($appointment['end_time']) : '' ?></li>
                <li>Beschrijving: <?= isset($appointment['description']) ? htmlspecialchars($appointment['description']) : '' ?></li>
            </ul>
        </section>

    <?php endforeach; ?>
    </section>
    <?php endif; ?>
</div>
</body>
</html>