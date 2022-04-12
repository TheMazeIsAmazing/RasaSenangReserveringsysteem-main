<?php
session_start();

$type = "rules";
$accept_reservations = "true";
$open_closed = "open";
$times_or_timeslots = "times";
$t_1600 = "true";
$t_1630 = "true";
$t_1700 = "true";
$t_1730 = "true";
$t_1800 = "true";
$t_1830 = "true";
$t_1900 = "true";
$t_1930 = "true";
$t_2000 = "true";
$t_2030 = "true";
$t_2100 = "true";

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

if (isset($_SESSION['daySettingChange']) && $_GET['edit'] == '1') {
    $settingID = $_SESSION['daySettingChange']['setting_id'];

    $queryPull = "SELECT * FROM `day-settings` WHERE id = '$settingID'";

    $resultPull = mysqli_query($db, $queryPull); //or die('Db Error: '.mysqli_error($db).' with query: '.$queryPull);

    $daySetting = mysqli_fetch_assoc($resultPull);

    if ($daySetting['from_date'] !== '') {
        $from_date = $daySetting['from_date'];
    }
    if ($daySetting['until_date'] !== '') {
        $until_date = $daySetting['until_date'];
    }

    $type = $daySetting['type'];
    $accept_reservations = $daySetting['accept_reservations'];
    $open_closed = $daySetting['open_closed'];
    $guest_limit = $daySetting['guest_limit'];
    $reservations_limit = $daySetting['reservations_limit'];

} else {
    unset($_GET);
    unset($_SESSION['canChangeEmployee']);
}


if (isset($_POST['submit'])) {
    if (isset($_SESSION['daySettingChange']) && $_GET['edit'] == '1') {
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

            $resultUpdate = mysqli_query($db, $queryUpdate); //or die('Db Error: '.mysqli_error($db).' with query: '.$queryUpdate);
            mysqli_close($db);
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
            mysqli_close($db);
            if ($resultRegister) {
                header('Location: ../medewerkers-instellingen/index.php');
                exit;
            }
        }
    }
}

print_r($daySetting);

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
if (isset($_SESSION['daySettingChange'])) {
    oneDotOrMoreHead('..', 'Daginstelling wijzigen bij Rasa Senang', false, false);
} else {
    oneDotOrMoreHead('..', 'Nieuwe Daginstelling voor Rasa Senan', false, false);
}
require_once "../includes/basic-elements/topBar.php";
oneDotOrMoreTopBar('..', './regels.php');
require_once "../includes/basic-elements/sideNav.php";
oneDotOrMoreNav('..', false);
?>
<main class="content-wrap">
    <header>
        <?php if (isset($_SESSION['daySettingChange'])) { ?>
            <h1>Daginstelling wijzigen</h1>
        <?php } else { ?>
            <h1>Nieuwe Daginstelling maken</h1>
        <?php } ?>
    </header>
    <form action="" method="post">
        <?php if ($type !== 'general') { ?>
            <div class="data-field">
                <div class="flexLabel" id ="daySettingsCreate">
                    <label for="from_date" class="loginLabel">Vanaf Datum:</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="date" name="from_date" value="<?= $from_date ?? '' ?>"/>
                    <span class="errors"><?= $errors['from_date'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel" id ="daySettingsCreate">
                    <label for="from_date" class="loginLabel">Tot en Met Datum:</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="date" name="from_date" value="<?= $until_date ?? '' ?>"/>
                    <span class="errors"><?= $errors['until_date'] ?? '' ?></span>
                </div>
            </div>
        <?php } ?>
        <div class="data-field">
            <div class="flexLabel" id ="daySettingsCreate">
                <label for="accept_reservations">Restaurant Geopend:</label>
            </div>
            <input type="checkbox"
                   name="accept_reservations" <?php if ($open_closed == "open") { ?> checked <?php } ?> />
        </div>
        <div class="hide-if-restaurant-closed">
            <div class="data-field">
                <div class="flexLabel" id ="daySettingsCreate">
                    <label for="accept_reservations">Accepteer Reserveringen:</label>
                </div>
                <input type="checkbox"
                       name="accept_reservations" <?php if ($accept_reservations == "true") { ?> checked <?php } ?> />
            </div>
            <div class="data-field">
                <div class="flexLabel" id ="daySettingsCreate">
                    <label for="guest_limit" class="loginLabel">Maximaal aantal gasten:</label>
                </div>
                <input type="number" name="guest_limit" value="<?= $guest_limit ?? '' ?>"/>
            </div>
            <div class="data-field">
                <div class="flexLabel" id ="daySettingsCreate">
                    <label for="reservations_limit" class="loginLabel">Maximaal aantal reserveringen:</label>
                </div>
                <input type="number" name="reservations_limit" value="<?= $reservations_limit ?? '' ?>"/>
            </div>
            <div>
                <div class="data-field">
                    <div class="flexLabel" id ="daySettingsCreate">
                        <label for="times_or_timeslots">Aanvangstijden of Tijdsloten:</label>
                    </div>
                    <select name="times_or_timeslots">
                        <option value="times" <?php if ($times_or_timeslots == "times") { ?> selected <?php } ?>>Aanvangstijden</option>
                        <option value="timeslots" <?php if ($times_or_timeslots == "timeslots") { ?> selected <?php } ?>>Tijdsloten</option>
                    </select>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel" id ="daySettingsCreate">
                    <label for="accept_reservations">Aanvangstijden:</label>
                </div>
                <input type="checkbox"
                       name="16:00" <?php if ($t_1600 == "true") { ?> checked <?php } ?> />
            </div>
        </div>
        <div class="data-submit">
            <?php if (isset($_SESSION['daySettingChange'])) { ?>
                <input type="submit" name="submit" value="Bevestigen"/>
            <?php } else { ?>
                <input type="submit" name="submit" value="Registreren"/>
            <?php } ?>
        </div>
    </form>
</main>
<?php require_once('../includes/basic-elements/footer.php');
oneDotOrMoreFooter('..'); ?>
