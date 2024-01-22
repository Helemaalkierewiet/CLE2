<?php
session_start();

$login = false;
$errors = [];

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $login = true;
}

/** @var mysqli $db */
require_once "includes/database.php";

if (isset($_POST['submit'])) {
    // Get form data with htmlspecialchars for added security
    $email = mysqli_real_escape_string($db, htmlspecialchars($_POST['email']));
    $password = mysqli_real_escape_string($db, htmlspecialchars($_POST['password']));

    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $user['user_id'];
                $_SESSION["email"] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $login = true;

                header("location: calender.php");
                exit();
            } else {
                //wrong password
                $errors['loginFailed'] = "Wrong login information";
            }
        } else {
            //user isn't stored in the database
            $errors['email'] = "User not found";
        }
    } else {
        //database problem
        $errors['database'] = "Error: " . mysqli_error($db);
    }

    mysqli_close($db);
}
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,500;1,800&display=swap"
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
    <div class="container">
    <?php if ($login):?>
<p> You are logged in!</p>
        <p><a href="logout.php">Log out</a> </p>
    <?php else: ?>
        <h2>Log in</h2>
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            </section>
    <div class="register-form">
  <form action="" method="post">
      <div class="register-form-all">
    <div class="register-form-part">
        <label class="label" for="email">Email</label>
        <div class="field">
            <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>" required/>
        </div>
    </div>
    <p>
        <?= $errors['email'] ?? '' ?>
    </p>
    <div class="register-form-part">
        <label class="label" for="password">Password</label>
        <div class="field">
            <input class="input" id="password" type="password" name="password" required/>
        </div>
    </div>
    <p>
        <?= $errors['password'] ?? '' ?>
    </p>

      <div class="field">
          <button type="submit" name="submit">Log in With Email</button>
      </div>
      </div>
  </form>
      <a href="register.php">Register</a>

      <div class="back-button">
          <a href="index.php">Back to home page</a>
      </div>
    </div>

</div>



    <?php endif; ?>
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
