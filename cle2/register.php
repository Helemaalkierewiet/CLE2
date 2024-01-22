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
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="screenshot-2023-12-11-135019-1-parent">
    <img
            class="screenshot-2023-12-11-135019-1"
            loading="eager"
            alt=""
            src="images/logo_144.png"
    />

    <div class="frame-parent">
        <div class="vector-parent">
            <img
                    class="frame-child"
                    alt=""
                    src="./public/rectangle-14.svg"
            />

            <a
                    class="home"
                    href="index.php"
                    target="_blank"
            >Home</a
            >
        </div>
        <div class="vector-group">
            <img
                    class="frame-item"
                    alt=""
                    src="./public/rectangle-15.svg"
            />

            <a
                    class="hoe-ziet-een"
                    href="https://boerderijweidelicht.nl/een-dag-op/"
                    target="_blank"
            >Hoe ziet een dag eruit</a
            >
        </div>
        <div class="vector-container">
            <img
                    class="frame-inner"
                    alt=""
                    src="./public/rectangle-16.svg"
            />

            <a
                    class="fotos"
                    href="https://www.figma.com/proto/OfRFP7CCgVH9oy64zSfQ4F/Untitled?type=design&node-id=21-43&t=5shvpIsrMsa1bECF-0&scaling=min-zoom&page-id=0%3A1"
                    target="_blank"
            >Foto´s</a
            >
        </div>
        <div class="frame-div">
            <img
                    class="rectangle-icon"
                    alt=""
                    src="./public/rectangle-18.svg"
            />

            <a
                    class="afspraak-maken"
                    href="calender.php"
                    target="_blank"
            >Afspraak maken</a
            >
        </div>
        <div class="vector-parent1">
            <img
                    class="frame-child1"
                    alt=""
                    src="./public/rectangle-17.svg"
            />

            <a
                    class="contact-locatie"
                    href="#divcontact"
                    target="_blank"
            >Contact & Locatie</a
            >
        </div>
        <button class="frame-button">
            <img
                    class="frame-child2"
                    alt=""
                    src="./public/rectangle-19.svg"
            />

            <a
                    class="login"
                    href="login.php"
                    target="_blank"
            >Login</a
            >
        </button>
    </div>
</header>

<div class="register-container">
    <section class="details-header">
        <h2>Register</h2>
    </section>
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
    <section class="register-form">
    <form action="" method="post">
        <div class="register-form-all">
        <div class="register-form-part">
            <label class="label" for="firstName">First name</label>
            <div class="field">
                <input class="input" id="firstName" type="text" name="firstName" value="<?= $firstName ?? '' ?>" />
            </div>
        </div>
        <div class="register-form-part">
            <label class="label" for="lastName">Last name</label>
            <div class="field">
                <input class="input" id="lastName" type="text" name="lastName" value="<?= $lastName ?? '' ?>" />
            </div>
        </div>

        <div class="register-form-part">
            <label class="label" for="email">Email</label>
            <div class="field">
                <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>" />
            </div>
        </div>

        <div class="register-form-part">
            <label class="label" for="password">Password</label>
            <div class="field">
                <input class="input" id="password" type="password" name="password"/>
            </div>
        </div>
        <div class="register-form-part">
            <div class="register-form-button">
                <button type="submit" name="submit">Register</button>
            </div>
        </div>
        </div>
    </form>
        <section>
        <div>
            <a href="login.php">Back to login</a>
            <a href="index.php">Back to home</a>
        </div>
        </section>
    </section>
</section>
</div>

<section class="rectangle-wrapper">
    <div class="rectangle">
        <div class="rectangle-child"></div>
        <div class="rectangle-inner">
            <div class="frame-group">
                <div class="text-parent" id="divcontact">
                    <div class="text6">
                        <div class="text-group">
                            <div class="text7">
                                <i class="contact">Contact</i>
                                <div class="line">
                                    <div class="whatsapp-logo-frame">
                                        <img
                                                class="whatsapp-logo-1"
                                                loading="eager"
                                                alt=""
                                                src="images/whatsapp%20logo.png"
                                        />

                                        <div class="whatsapp-06-">
                                            whatsapp: 06 - 27 51 09 11
                                        </div>
                                    </div>
                                    <div class="whatsapp-logo-frame1">
                                        <img
                                                class="telefoon-logo-1"
                                                loading="eager"
                                                alt=""
                                                src="images/telefoon%20logo.jpg"
                                        />

                                        <div class="div">015-3809999</div>
                                    </div>
                                </div>
                            </div>
                            <div class="locatie-logo-frame">
                                <img
                                        class="locatie-logo-1"
                                        loading="eager"
                                        alt=""
                                        src="images/locatie%20logo.jpg"
                                />

                                <div class="oostveenseweg-7-2636">
                                    Oostveenseweg 7, 2636 EC Schipluiden
                                </div>
                            </div>
                        </div>
                        <div class="mail-logo-frame">
                            <img
                                    class="mail-logo-1"
                                    loading="eager"
                                    alt=""
                                    src="images/mail%20logo.jpg"
                            />

                            <div class="infoboerderijweidelichtnl">
                                info@boerderijweidelicht.nl
                            </div>
                        </div>
                    </div>
                    <div class="locatie-parent">
                        <i class="locatie">Locatie</i>
                        <div class="locatieboederij-1-wrapper">
                            <img
                                    class="locatieboederij-1-icon"
                                    loading="eager"
                                    alt=""
                                    src="images/locatieboederij.png"
                            />
                        </div>
                    </div>
                </div>
                <div class="nieuws-frame">
                    <i class="nieuws">Nieuws</i>
                    <div class="de-keuken-het-container">
                  <span>
                    <p class="de-keuken-het">
                      De keuken: Het middelpunt van een huishouden, ook bij ons!
                    </p>
                    <p class="blank-line14">&nbsp;</p>
                    <p class="en-toen-was">
                      En toen was er ineens.... een kalfje!
                    </p>
                    <p class="blank-line15">&nbsp;</p>
                    <p class="heel-blij-met">Heel blij met de duo-fiets!</p>
                  </span>
                    </div>
                </div>
            </div>
        </div>
        <footer class="line-element">
            <div class="copyright-frame"></div>
            <div class="copyright-2019-all-rights-re-wrapper">
                <div class="copyright-2019">
                    Copyright 2019 | All Rights Reserved by Boerderij Weidelicht
                </div>
            </div>
        </footer>
    </div>
</section>

</body>
</html>