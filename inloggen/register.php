<?php
session_start();

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: ../inloggen/");
    exit;
}


if ($_SESSION['loggedInUser']['can_visit_employees'] !== "true") {
    header("Location: ../medewerkers/");
    exit;
}

/*
if (isset($_SESSION['canChangeEmployee']) && !isset($_GET)) {
    unset($_SESSION['canChangeEmployee']);
    header('Location: ./register.php');
    exit;
}

if (!isset($_SESSION['canChangeEmployee']) && isset($_GET)) {
    unset($_GET);
    header('Location: ./register.php');
    exit;
} */

$can_visit_reservations = "false";
$can_visit_employees = "false";
$can_visit_daysettings = "false";
$can_visit_table = "false";

require_once '../includes/database.php';
/** @var mysqli $db */
require_once "../includes/footer.php";
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');

if (isset($_SESSION['canChangeEmployee']) && $_GET['edit'] == '1') {
    $employeeID = $_SESSION['canChangeEmployee']['employee_id'];

    $queryPull = "SELECT * FROM users WHERE id = '$employeeID'";

    $resultPull = mysqli_query($db, $queryPull); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);

    $employee = mysqli_fetch_assoc($resultPull);
    $username = $employee['username'];
    $name = $employee['name'];
    $can_visit_reservations = $employee['can_visit_reservations'];
    $can_visit_employees = $employee['can_visit_employees'];
    $can_visit_daysettings = $employee['can_visit_daysettings'];
    $can_visit_table = $employee['can_visit_table'];
} else {
    unset($_GET);
    unset($_SESSION['canChangeEmployee']);
    //header('Location: ./register.php');
    //exit;
}


if (isset($_POST['submit'])) {
    if (isset($_SESSION['canChangeEmployee']) && $_GET['edit'] == '1') {
        $username = mysqli_escape_string($db, $_POST['username']);
        $name = mysqli_escape_string($db, $_POST['name']);

        if (isset($_POST['can_visit_reservations'])) {
            $can_visit_reservations = 'true';
        } else {
            $can_visit_reservations = 'false';
        }

        if (isset($_POST['can_visit_employees'])) {
            $can_visit_employees = 'true';
        } else {
            $can_visit_employees = 'false';
        }

        if (isset($_POST['can_visit_daysettings'])) {
            $can_visit_daysettings = 'true';
        } else {
            $can_visit_daysettings = 'false';
        }

        if (isset($_POST['can_visit_table'])) {
            $can_visit_table = 'true';
        } else {
            $can_visit_table = 'false';
        }

        $errors = [];
        if ($name == '') {
            $errors['name'] = 'Voer een Naam in';
        }
        if ($username == '') {
            $errors['username'] = 'Voer een gebruikersnaam in';
        }


        if (empty($errors)) {

            $queryUpdate = "UPDATE `users` SET username = '$username', name = '$name', can_visit_reservations = '$can_visit_reservations', can_visit_daysettings = '$can_visit_daysettings', can_visit_table = '$can_visit_table', can_visit_employees = '$can_visit_employees' WHERE id = '$employeeID'";

            $resultUpdate = mysqli_query($db, $queryUpdate); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);

            if ($resultUpdate) {
                header('Location: ../medewerkers-instellingen/details.php?id=' . $employeeID);
                exit;
            }
        }
    } else {
        $username = mysqli_escape_string($db, $_POST['username']);
        $name = mysqli_escape_string($db, $_POST['name']);
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        $errors = [];
        if ($name == '') {
            $errors['name'] = 'Voer een Naam in';
        }
        if ($username == '') {
            $errors['username'] = 'Voer een gebruikersnaam in';
        }
        if ($password == '') {
            $errors['password'] = 'Voer een wachtwoord in';
        }
        if ($password !== $passwordConfirm) {
            $errors['password'] = 'De wachtwoorden komen niet overeen';
        }

        if (empty($errors)) {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $queryRegister = "INSERT INTO users (username, password, name ) VALUES ('$username', '$password', '$name')";

            $resultRegister = mysqli_query($db, $queryRegister); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);

            if ($resultRegister) {
                header('Location: ../medewerkers-instellingen/index.php');
                exit;
            }
        }
    }

}
?>
<!doctype html>
<html lang="nl">
<head>
    <?php if (isset($_SESSION['canChangeEmployee'])) { ?>
        <title>Medewerker wijzigen bij Rasa Senang</title>
    <?php } else { ?>
        <title>Nieuwe Medewerker bij Rasa Senang</title>
    <?php } ?>
</head>
<body>
<header>
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="../medewerkers-instellingen">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Beginpagina">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
    <main>
        <div class="content-wrap">
            <div>
                <?php if (isset($_SESSION['canChangeEmployee'])) { ?>
                    <h1>Medewerker aanpassen</h1>
                <?php } else { ?>
                    <h1>Nieuwe medewerker registeren</h1>
                <?php } ?>
            </div>
            <form action="" method="post">
                <div class="data-field">
                    <label for="name">Naam</label>
                    <input type="text" name="name" value="<?= $name ?? '' ?>"/>
                    <span class="errors"><?= $errors['name'] ?? '' ?></span>
                </div>
                <div class="data-field">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" name="username" value="<?= $username ?? '' ?>"/>
                    <span class="errors"><?= $errors['username'] ?? '' ?></span>
                </div>
                <?php if (!isset($_SESSION['canChangeEmployee']) && !isset($_GET['edit'])) { ?>
                    <div class="data-field">
                        <label for="password">Wachtwoord</label>
                        <input type="password" name="password" value="<?= $password ?? '' ?>"/>
                        <span class="errors"><?= $errors['password'] ?? '' ?></span>
                    </div>
                    <div class="data-field">
                        <label for="password">Wachtwoord Bevestigen</label>
                        <input type="password" name="passwordConfirm" value="<?= $passwordConfirm ?? '' ?>"/>
                    </div>
                <?php } ?>
                <h3 class="h3detailsEmp">Rechten:</h3>
                <div class="data-field">
                    <label for="password">Overzicht Reserveringen:</label>
                    <input type="checkbox"
                           name="can_visit_reservations" <?php if ($can_visit_reservations == "true") { ?> checked <?php } ?> />
                </div>
                <div class="data-field">
                    <label for="password">Overzicht Medewerkers:</label>
                    <input type="checkbox"
                           name="can_visit_employees" <?php if ($can_visit_employees == "true") { ?> checked <?php } ?>/>
                </div>
                <div class="data-field">
                    <label for="password" style="display: none;">Daginstellingen:</label>
                    <input type="checkbox" name="can_visit_table"
                           style="display: none;" <?php if ($can_visit_daysettings == "true") { ?> checked <?php } ?>/>
                </div>
                <div class="data-field">
                    <label for="password" style="display: none;">Tafelindeling:</label>
                    <input type="checkbox" name="can_visit_table"
                           style="display: none;" <?php if ($can_visit_table == "true") { ?> checked <?php } ?>/>
                </div>
                <div class="data-submit">
                    <?php if (isset($_SESSION['canChangeEmployee'])) { ?>
                        <input type="submit" name="submit" value="Bevestigen"/>
                    <?php } else { ?>
                        <input type="submit" name="submit" value="Registreren"/>
                    <?php } ?>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
