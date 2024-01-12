<?php

/** @var mysqli $db */

require_once 'database.php';


// ^^SESSION CHECK^^ //

//session_start();
//// May I visit this page? Check the SESSION
//if (!isset($_SESSION['user'])) {
//    // Redirect if not logged in
//    header('Location: login.php');
//    exit; }


// Maak empty vars

$errorDay = "";
$errorTimeslot = "";

// filled in? check

// Is de Form correct is ingevuld?
if (
    isset($_POST['ID'], $_POST['soort_stoel'], $_POST['merk'], $_POST['kleur'],
        $_POST['materiaal_zitting'], $_POST['materiaal_onderstel'], $_POST['arm_leuning'],
        $_POST['stoffering'], $_POST['prijs'])
) {
// Zo nee, stuur een Error Message naar het betreffende veld
    if (empty($_POST['ID'])) {
        $errorID = 'Het ID kan niet leeg zijn hoor';
    }

    if (empty($_POST['soort_stoel'])) {
        $errorSoortStoel = 'De Soort kan niet leeg zijn hoor';
    }

    if (empty($_POST['merk'])) {
        $errorMerk = 'Het Merk kan niet leeg zijn hoor';
    }

    if (empty($_POST['kleur'])) {
        $errorKleur = 'De Kleur kan niet leeg zijn hoor';
    }

    if (empty($_POST['materiaal_zitting'])) {
        $errorMateriaalzitting = 'Het Materiaal van de zitting kan niet leeg zijn hoor';
    }

    if (empty($_POST['materiaal_onderstel'])) {
        $errorMateriaalonderstel = 'Het Materiaal van het onderstel kan niet leeg zijn hoor';
    }

    if (empty($_POST['arm_leuning'])) {
        $errorLeuning = 'Leuning kan niet leeg zijn hoor';
    }

    if (empty($_POST['stoffering'])) {
        $errorStoffering = 'Stoffering kan niet leeg zijn hoor';
    }

    if (empty($_POST['prijs'])) {
        $errorStoffering = 'DE PRIJS kan niet leeg zijn hoor';
    }
}
// roep actie if statement op als er op submit geklikt word
if (isset($_POST['submit'])) {
    // Other form validations...

    if (empty($errorID) && empty($errorSoortStoel) && empty($errorMerk) && empty($errorKleur)
        && empty($errorMateriaalzitting) && empty($errorMateriaalonderstel) && empty($errorLeuning)
        && empty($errorStoffering) && empty($errorPrijs)) {

        $ID = $_POST['ID'];
        $soortStoel = $_POST['soort_stoel'];
        $merk = $_POST['merk'];
        $kleur = $_POST['kleur'];
        $materiaalZitting = $_POST['materiaal_zitting'];
        $materiaalOnderstel = $_POST['materiaal_onderstel'];
        $armLeuning = $_POST['arm_leuning'];
        $stoffering = $_POST['stoffering'];
        $prijs = $_POST['prijs'];

        // Check if the ID already exists in the database
        $check_query = "SELECT ID FROM stoelen WHERE ID = '$ID'";
        $check_result = mysqli_query($db, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // ID already exists, show an error message
            $errorID = 'Dit ID is al in gebruik';
        } else {
            // ID does not exist, proceed with the insertion
            $query = "INSERT INTO stoel_catalogus.stoelen (ID, soort_stoel, merk, kleur,
                             materiaal_zitting, materiaal_onderstel, arm_leuning, stoffering, prijs) 
                             VALUES ('$ID', '$soortStoel', '$merk', '$kleur', 
                             '$materiaalZitting', '$materiaalOnderstel', '$armLeuning', '$stoffering', '$prijs')";

            // Execute the query
            $result = mysqli_query($db, $query);

            if ($result) {
                // Redirect if successful
                header('Location: secure.php');
                exit();
            } else {
                // Display an error message if the query failed
                echo 'Error: ' . mysqli_error($db);
            }
        }

    }
}

//if (isset[$_POST['artist']]) {}



// IF FORM VALIDATION BESTAND, dus require_once 'includes-FORM-VALIDATION
require_once 'includes/form-validation.php';

//Get name from the SESSION
$name = $_SESSION['user']['name'];

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
<form action="" method="post">

    <div>
        <label for="ID">ID</label>
    </div>
    <div class="control">
        <input id="ID" class="input is-hovered" type="number" placeholder="ID" name="ID">
        <p> <?php echo $errorID ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Soort" name="soort_stoel">
        <p> <?php echo $errorSoortStoel ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Merk" name="merk">
        <p> <?php echo $errorMerk ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Kleur" name="kleur">
        <p> <?php echo $errorKleur ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Materiaal zitting" name="materiaal_zitting">
        <p> <?php echo $errorMateriaalzitting ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Materiaal onderstel" name="materiaal_onderstel">
        <p> <?php echo $errorMateriaalonderstel ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Leuning" name="arm_leuning">
        <p> <?php echo $errorLeuning ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="Stoffering" name="stoffering">
        <p> <?php echo $errorStoffering ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="text" placeholder="PrijsMoneyGang" name="prijs">
        <p> <?php echo $errorPrijs ?> </p>
    </div>
    <div class="control">
        <input class="input is-hovered" type="submit" name="submit" value="Submit">
    </div>

</form>

</body>
</html>
