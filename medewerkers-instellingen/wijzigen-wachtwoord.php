<?php
session_start();

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();

if (isset($_POST['submit'])) {
    $passwordEmployee = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    $errors = [];

    if ($passwordEmployee == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }
    if ($passwordConfirm !== $passwordEmployee && $passwordEmployee !== '') {
        $errors['passwordConfirm'] = 'De wachtwoorden komen niet overeen';
    }
    if (empty($errors)) {
        $passwordEmployee = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $employeeID = $_SESSION['loggedInUser']['id'];
        $queryUpdate = "UPDATE `users` SET password = '$passwordEmployee' WHERE id = '$employeeID'";

        $resultUpdate = mysqli_query($db, $queryUpdate); //or die('Db Error: ' . mysqli_error($db) . ' with query: ' . $queryUpdate);
        if ($resultUpdate) {
            $os = getOS();
            $client = getBrowser();

            $nameEmp = mysqli_escape_string($db, $_SESSION['loggedInUser']['name']);
            $queryNewLog = "INSERT INTO `logs` (`user_status`, `user_name`, `class`, `action`, `id_of_class`, `browser`, `os`) VALUES ('employee', '$nameEmp', 'employee', 'change', '$employeeID', '$client', '$os')";

            $resultNewLog = mysqli_query($db, $queryNewLog); //or die('Error: ' . mysqli_error($db) . ' with query ' . $queryNewLog);

            mysqli_close($db);

            header('Location: ./details.php?error=employeeChangedSuccessful');
            exit;
        }
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
initializeHead('..', 'Medewerker wijzigen bij Rasa Senang',false, false, false, false);
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', './');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
    <main class="content-wrap">
        <header>
            <h1>Wachtwoord aanpassen</h1>
        </header>
        <form action="" method="post">
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
            <div class="data-submit">
                <input type="submit" name="submit" value="Bevestigen"/>
            </div>
        </form>
    </main>
    <?php require_once('../includes/basic-elements/footer.php');
    initializeFooter('..'); ?>>
