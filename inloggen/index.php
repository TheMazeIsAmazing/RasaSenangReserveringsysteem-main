<?php
session_start();

if (isset($_SESSION['loggedInUser'])) {
    header('Location: ../medewerkers');
    exit;
}

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

if (isset($_GET)) {
    if (isset($_GET['error'])) {
        $errorType = $_GET['error'];
    }
}

if (isset($_POST['submit'])) {
    $usernameInput = mysqli_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    $errors = [];
    if ($usernameInput == '') {
        $errors['username'] = 'Voer een gebruikersnaam in';
    }
    if ($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }

    if (empty($errors)) {
        //Get record from DB based on first name
        $query = "SELECT * FROM users WHERE username='$usernameInput'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {

                $_SESSION['loggedInUser'] = [
                    'id' => mysqli_escape_string($db, $user['id']),
                    'username' => mysqli_escape_string($db, $user['username']),
                    'name' => mysqli_escape_string($db, $user['name']),
                    'can_visit_reservations' => mysqli_escape_string($db, $user['can_visit_reservations']),
                    'can_visit_daysettings' => mysqli_escape_string($db, $user['can_visit_daysettings']),
                    'can_visit_table' => mysqli_escape_string($db, $user['can_visit_table']),
                    'can_visit_employees' => mysqli_escape_string($db, $user['can_visit_employees']),
                    'is-admin' => mysqli_escape_string($db, $user['is-admin'])
                ];
                header('Location: ../medewerkers');
                exit;
            } else {
                //error onjuiste inloggegevens
                $errors['loginFailed'] = 'De combinatie van Gebruikersnaam en Wachtwoord is bij ons niet bekend';
            }
            mysqli_close($db);
        } else {
            //error onjuiste inloggegevens
            $errors['loginFailed'] = 'De combinatie van Gebruikersnaam en Wachtwoord is bij ons niet bekend';
            mysqli_close($db);
        }
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
initializeHead('..', 'Inloggen bij Rasa Senang', false, false, false, false);
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', '../');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
<main class="content-wrap">
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
                <input type="text" name="username" value="<?= $usernameInput ?? '' ?>"/>
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
</main>
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>
