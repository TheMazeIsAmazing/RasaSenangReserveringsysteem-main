<?php
session_start();

if ((isset($_GET['edit']) && $_GET['edit'] !== '1') || (isset($_GET['edit']) && !isset($_SESSION['canChangeEmployee'])) || (!isset($_GET['edit']) && isset($_SESSION['canChangeEmployee']))) {
    if (isset($_SESSION['canChangeEmployee'])) {
        unset($_SESSION['canChangeEmployee']);
    }
    if (isset($_GET['edit'])) {
        unset($_GET['edit']);
    }
    header('location: ./');
    exit;
}

$commonPasswordList = ["123456", "123456789", "password", "password123", "passw0rd", "12345678", "111111", "123123", "12345", "1234567890", "1234567", "qwerty", "abc123", "000000", "1234", "iloveyou", "password1", "123", "123321", "654321", "qwertyuiop", "123456a", "a123456", "666666", "asdfghjkl", "987654321", "zxcvbnm", "112233", "20100728", "123123123", "princess", "123abc", "123qwe", "sunshine", "121212", "dragon", "1q2w3e4r", "159753", "0123456789", "pokemon", "qwerty123", "monkey", "1qaz2wsx", "abcd1234", "aaaaaa", "soccer", "123654", "12345678910", "shadow", "102030", "11111111", "asdfgh", "147258369", "qazwsx", "qwe123", "football", "baseball", "person", "government", "company", "number", "problem", "0123456"];


$can_visit_reservations = "false";
$can_visit_employees = "false";
$can_visit_daysettings = "false";
$can_visit_table = "false";

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
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
} else {
    unset($_GET);
    unset($_SESSION['canChangeEmployee']);
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
        if ($passwordEmployee == '') {
            $errors['password'] = 'Voer een wachtwoord in';
        }
        if ($passwordConfirm == '' && $passwordEmployee !== '') {
            $errors['passwordConfirm'] = 'Vul opnieuw het wachtwoord in.';
        }

        if ($passwordConfirm !== '' && $passwordEmployee !== '' && $passwordEmployee !== $passwordConfirm) {
            $errors['password'] = 'De wachtwoorden komen niet overeen';
        }

        if (empty($errors['password'])) {
            foreach ($commonPasswordList as $commonPassword) {
                if ($passwordEmployee == $commonPassword) {
                    $errors['password'] = "Het gekozen wachtwoord voldoet niet aan de wachtwoordvereisten: .";
                }
            }
        }

        if (empty($errors['password'])) {
                if (strlen($passwordEmployee) < 8) {
                    $errors['password'] = "Het gekozen wachtwoord voldoet niet aan de wachtwoordvereisten.";
                } else {
                    if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $passwordEmployee)) {
                        $errors['password'] = "Het gekozen wachtwoord voldoet niet aan de eisen.";;
                    }
                }
        }

        if (empty($errors)) {
            //check if there is another user with the same username, if the case give error message
            $queryPullEmployees = "SELECT * FROM users";
            //Get the result set from the database with a SQL query
            $resultPullEmployees = mysqli_query($db, $queryPullEmployees); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
            //Loop through the result to create a custom array
            $employeesRegistered = [];
            while ($row = mysqli_fetch_assoc($resultPullEmployees)) {
                $employeesRegistered[] = $row;
            }

            foreach ($employeesRegistered as $employeeRegistered) {
                if ($employeeRegistered['username'] == $user) {
                    $errors['username'] = 'Er is een andere medewerker met dezelfde gebruikersnaam, kies een andere.';
                }
            }

            if (empty($errors)) {
                $passwordEmployee = password_hash($passwordEmployee, PASSWORD_DEFAULT);

                $queryRegister = "INSERT INTO `users` (`username`, `password`, `name`, `can_visit_reservations`, `can_visit_daysettings`, `can_visit_table`, `can_visit_employees`) VALUES ('$user', '$passwordEmployee', '$name', '$can_visit_reservations', '$can_visit_daysettings', '$can_visit_table', '$can_visit_employees');";
                $resultRegister = mysqli_query($db, $queryRegister); //or die('Db Error: '.mysqli_error($db).' with query: '.$query);
                mysqli_close($db);
                if ($resultRegister) {
                    header('Location: ../medewerkers-instellingen/index.php');
                    exit;
                }
            }
        }
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
if (isset($_SESSION['canChangeEmployee'])) {
    initializeHead('..', 'Medewerker wijzigen bij Rasa Senang', false, false, false, true);
} else {
    initializeHead('..', 'Medewerker registreren bij Rasa Senan', false, false, false, true);
}
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', './');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
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
                    <input type="password" id="passwordField" name="password" oninput="checkPassword()" value="<?= $passwordEmployee ?? '' ?>" />
                    <span class="errors"><?= $errors['password'] ?? '' ?></span>
                </div>
            </div>
        <div class="data-field">
            <div class="flexLabel">
            </div>
            <div>
                <div id="passwordStrengthContainer">
                    <div id="passwordStrengthBar"></div><div id="passwordStrengthText"></div>
                </div>
                <div id="passwordInfoPanel">
                    Wachtwoordvereisten:
                    <ul>
                        <li id="passwordRequirementMinLength">Bevat minimaal 8 karakters</li>
                        <li id="passwordRequirementChars">Bevat zowel letters als nummers</li>
                        <li id="passwordRequirementCommonPasswords">Is niet gemakkelijk te raden</li>
                    </ul>
                </div>
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
        <h3 class="h3detailsEmp">Deze gebruiker mag naar de volgende pagina's:</h3>
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
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>
