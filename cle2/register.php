<?php
if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "includes/database.php";

    $firstName = mysqli_real_escape_string($db, htmlspecialchars($_POST['firstName'] ?? ''));
    $lastName = mysqli_real_escape_string($db, htmlspecialchars($_POST['lastName'] ?? ''));
    $email = mysqli_real_escape_string($db, htmlspecialchars($_POST['email'] ?? ''));
    $password = mysqli_real_escape_string($db, htmlspecialchars($_POST['password'] ?? ''));

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        echo "All fields are required. Please fill in the form.";
        echo '<br><a href="register.php"><button type="button">Go Back</button></a>';
        exit();
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    if (empty($errors)) {

        if (!$db) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Use prepared statement to prevent SQL injection
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($db, "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $passwordHash);

        if (mysqli_stmt_execute($stmt)) {
            echo "New record created successfully";

            header("Location: http://localhost/CLE2/cle2/login.php");
        } else {
            echo "Error: " . mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($db);
}
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
<h2>Register</h2>
<section>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <div>
            <label class="label" for="firstName">First name</label>
            <div class="field">
                <input class="input" id="firstName" type="text" name="firstName" value="<?= $firstName ?? '' ?>" />
            </div>
        </div>
        <div>
            <label class="label" for="lastName">Last name</label>
            <div class="field">
                <input class="input" id="lastName" type="text" name="lastName" value="<?= $lastName ?? '' ?>" />
            </div>
        </div>

        <div>
            <label class="label" for="email">Email</label>
            <div class="field">
                <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>" />
            </div>
        </div>

        <div>
            <label class="label" for="password">Password</label>
            <div class="field">
                <input class="input" id="password" type="password" name="password"/>
            </div>
        </div>
        <div>
            <div>
                <button type="submit" name="submit">Register</button>
            </div>
        </div>
    </form>
</section>
</body>
</html>