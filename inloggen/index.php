<?php
session_start();

if (isset($_SESSION['loggedInUser'])) {
    $login = true;
} else {
    $login = false;
}

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');

if (isset($_GET)) {
    if (isset($_GET['error'])) {
        $errorType = $_GET['error'];
    }
}

if (isset($_POST['submit'])) {
    $user = mysqli_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    $errors = [];
    if ($user == '') {
        $errors['username'] = 'Voer een gebruikersnaam in';
    }
    if ($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }

    if (empty($errors)) {
        //Get record from DB based on first name
        $query = "SELECT * FROM users WHERE username='$user'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $login = true;

                $_SESSION['loggedInUser'] = [
                    'username' => mysqli_escape_string($db, $user['username']),
                    'name' => mysqli_escape_string($db, $user['name']),
                    'can_visit_reservations' => mysqli_escape_string($db, $user['can_visit_reservations']),
                    'can_visit_daysettings' => mysqli_escape_string($db, $user['can_visit_daysettings']),
                    'can_visit_table' => mysqli_escape_string($db, $user['can_visit_table']),
                    'can_visit_employees' => mysqli_escape_string($db, $user['can_visit_employees'])
                ];
            } else {
                //error onjuiste inloggegevens
                $errors['loginFailed'] = 'De combinatie van Gebruikersnaam en Wachtwoord is bij ons niet bekend';
            }
        } else {
            //error onjuiste inloggegevens
            $errors['loginFailed'] = 'De combinatie van Gebruikersnaam en Wachtwoord is bij ons niet bekend';
        }
    }
}
?>
<!doctype html>
<html lang="nl">
<head>
    <title>Inloggen bij Rasa Senang</title>
</head>

<body>

<div class="overlay"></div>


<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="../">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Beginpagina">
        </button>
    </a>
</header>

<div class="page-container">
    <main class="content-wrap">
        <?php if ($login) {
            header('Location: ../medewerkers');
        } else { ?>
            <header>
                <h1>Inloggen Medewerkers</h1>
            </header>
            <?php if (isset($errors['loginFailed'])) { ?>
                <div class="errorLoginNegative">
                    <div class="message">
                        <?= $errors['loginFailed'] ?>
                    </div>
                </div>
            <?php } elseif (isset($errorType)) {
                if ($errorType == 'logoutSuccessful') { ?>
                    <div class="errorLoginPositive">
                        <div class="message">
                            U bent succesvol Uitgelogd!
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="errorLoginNegative">
                        <div class="message">
                            U moet eerst inloggen voordat u deze pagina kunt bezoeken!
                        </div>
                    </div>
                <?php }
            } ?>
            <form action="" method="post">
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="username" class="loginLabel">Gebruikersnaam</label>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="text" name="username" value="<?= $user ?? '' ?>"/>
                        <span class="errors"><?= $errors['username'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="password" class="loginLabel">Wachtwoord</label>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="password" name="password"/>
                        <span class="errors"><?= $errors['password'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-submit">
                    <input type="submit" name="submit" value="Login"/>
                </div>
            </form>
        <?php } ?>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
