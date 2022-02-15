<?php
session_start();

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//May I even visit this page?
require_once "../includes/logincheck.php";
loginCheck();

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');

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
            header('Location: ../medewerkers-instellingen/details.php?id=' . $employeeID);
            exit;
        }
    }

}
?>
<!doctype html>
<html lang="nl">
<head>
    <title>Medewerker wijzigen bij Rasa Senang</title>

</head>
<body>
<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="./">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Beginpagina">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
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
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
