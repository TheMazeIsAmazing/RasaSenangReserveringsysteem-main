<?php
session_start();

/*
if (isset($_SESSION['canChangeEmployee']) && !isset($_GET)) {
    unset($_SESSION['canChangeEmployee']);
    header('Location: ./nieuwe-gebruiker.php');
    exit;
}

if (!isset($_SESSION['canChangeEmployee']) && isset($_GET)) {
    unset($_GET);
    header('Location: ./nieuwe-gebruiker.php');
    exit;
} */

$can_visit_reservations = "false";
$can_visit_employees = "false";
$can_visit_daysettings = "false";
$can_visit_table = "false";

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//May I even visit this page?
require_once "../includes/logincheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_employees');

if (isset($_SESSION['canChangeEmployee']) && $_GET['edit'] == '1') {
    $employeeID = $_SESSION['canChangeEmployee']['employee_id'];

    $queryPull = "SELECT * FROM users WHERE id = '$employeeID'";

    $resultPull = mysqli_query($db, $queryPull); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);

    $employee = mysqli_fetch_assoc($resultPull);
    $user = $employee['username'];
    $name = $employee['name'];
    $can_visit_reservations = $employee['can_visit_reservations'];
    $can_visit_employees = $employee['can_visit_employees'];
    $can_visit_daysettings = $employee['can_visit_daysettings'];
    $can_visit_table = $employee['can_visit_table'];
    mysqli_close($db);
} else {
    unset($_GET);
    unset($_SESSION['canChangeEmployee']);
    //header('Location: ./nieuwe-gebruiker.php');
    //exit;
}


if (isset($_POST['submit'])) {
    if (isset($_SESSION['canChangeEmployee']) && $_GET['edit'] == '1') {
        $user = mysqli_escape_string($db, $_POST['username']);
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
        if ($user == '') {
            $errors['username'] = 'Voer een gebruikersnaam in';
        }


        if (empty($errors)) {

            $queryUpdate = "UPDATE `users` SET username = '$user', name = '$name', can_visit_reservations = '$can_visit_reservations', can_visit_daysettings = '$can_visit_daysettings', can_visit_table = '$can_visit_table', can_visit_employees = '$can_visit_employees' WHERE id = '$employeeID'";

            $resultUpdate = mysqli_query($db, $queryUpdate); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);

            if ($resultUpdate) {
                header('Location: ../medewerkers-instellingen/details.php?id=' . $employeeID);
                exit;
            }
        }
    } else {
        $user = mysqli_escape_string($db, $_POST['username']);
        $name = mysqli_escape_string($db, $_POST['name']);
        $passwordEmployee = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        $errors = [];
        if ($name == '') {
            $errors['name'] = 'Voer een Naam in';
        }
        if ($user == '') {
            $errors['username'] = 'Voer een gebruikersnaam in';
        }
        if ($passwordEmployee == '') {
            $errors['password'] = 'Voer een wachtwoord in';
        }
        if ($passwordConfirm == '' && $passwordEmployee !== '') {
            $errors['passwordConfirm'] = 'Vul opnieuw het wachtwoord in.';
        }

        if ($passwordConfirm !== '' && $passwordEmployee !== '' && $passwordEmployee !== $passwordConfirm) {
            $errors['password'] = 'De wachtwoorden komen niet overeen';
        }

        if (empty($errors)) {
            $passwordEmployee = password_hash($passwordEmployee, PASSWORD_DEFAULT);

            $queryRegister = "INSERT INTO users (username, password, name ) VALUES ('$user', '$passwordEmployee', '$name')";

            $resultRegister = mysqli_query($db, $queryRegister); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);

            if ($resultRegister) {
                header('Location: ../medewerkers-instellingen/index.php');
                exit;
            }
        }
    }
}

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
if (isset($_SESSION['canChangeEmployee'])) {
    oneDotOrMoreHead('..', 'Medewerker wijzigen bij Rasa Senang');
} else {
    oneDotOrMoreHead('..', 'Medewerker registreren bij Rasa Senan');
}
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', './');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

<div class="overlay"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <?php if (isset($_SESSION['canChangeEmployee'])) { ?>
                <h1>Medewerker aanpassen</h1>
            <?php } else { ?>
                <h1>Nieuwe medewerker registeren</h1>
            <?php } ?>
        </header>
        <form action="" method="post">
            <div class="data-field">
                <div class="flexLabel">
                    <label for="name" class="loginLabel">Naam</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="text" name="name" value="<?= $name ?? '' ?>"/>
                    <span class="errors"><?= $errors['name'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="username" class="loginLabel">Gebruikersnaam</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="text" name="username" value="<?= $user ?? '' ?>"/>
                    <span class="errors"><?= $errors['username'] ?? '' ?></span>
                </div>
            </div>
            <?php if (!isset($_SESSION['canChangeEmployee']) && !isset($_GET['edit'])) { ?>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="password">Wachtwoord</label>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="password" name="password" value="<?= $passwordEmployee ?? '' ?>"/>
                        <span class="errors"><?= $errors['password'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="passwordConfirm">Wachtwoord Bevestigen</label>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="password" name="passwordConfirm" value="<?= $passwordConfirm ?? '' ?>"/>
                        <span class="errors"><?= $errors['passwordConfirm'] ?? '' ?></span>
                    </div>
                </div>
            <?php } ?>
            <h3 class="h3detailsEmp">Rechten:</h3>
            <div class="employeeRights">
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="can_visit_reservations">Overzicht Reserveringen:</label>
                    </div>
                    <input type="checkbox"
                           name="can_visit_reservations" <?php if ($can_visit_reservations == "true") { ?> checked <?php } ?> />
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="can_visit_employees">Overzicht Medewerkers:</label>
                    </div>
                    <input type="checkbox"
                           name="can_visit_employees" <?php if ($can_visit_employees == "true") { ?> checked <?php } ?> />
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="can_visit_daysettings">Daginstellingen:</label>
                    </div>
                    <input type="checkbox"
                           name="can_visit_daysettings" <?php if ($can_visit_daysettings == "true") { ?> checked <?php } ?> />
                </div>
                <div class="data-field" style="display: none;">
                    <div class="flexLabel">
                        <label for="can_visit_table">Tafelindeling:</label>
                    </div>
                    <input type="checkbox"
                           name="can_visit_table" <?php if ($can_visit_table == "true") { ?> checked <?php } ?> />
                </div>
            </div>
            <div class="data-submit">
                <?php if (isset($_SESSION['canChangeEmployee'])) { ?>
                    <input type="submit" name="submit" value="Bevestigen"/>
                <?php } else { ?>
                    <input type="submit" name="submit" value="Registreren"/>
                <?php } ?>
            </div>
        </form>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
