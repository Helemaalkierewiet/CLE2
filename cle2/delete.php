<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["loggedin"]) || !$_SESSION["is_admin"]) {
    // Redirect to the login page or handle accordingly
    header("Location: login.php");
    exit();
}
/**
 * @var mysqli $db
 */

require_once 'includes/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Perform the deletion
    $deleteAppointmentQuery = "DELETE FROM appointments WHERE id = $id";
    $deleteAppointmentResult = mysqli_query($db, $deleteAppointmentQuery);

    if ($deleteAppointmentResult) {
        header("Location: secure.php");
        exit();
    } else {
        echo "Error deleting appointment: " . mysqli_error($db);
    }
} else {
    echo "Invalid request. ID not provided.";
}
