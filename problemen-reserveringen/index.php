<?php
session_start();

require_once '../mailer.php';

if (isset($_GET)) {
    if (isset($_GET['error'])) {
        $errorType = $_GET['error'];
        unset($name);
        unset($date);
        unset($email);
        unset($message);
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $email = $_POST['email'];
    $message = htmlspecialchars($_POST['message']);

    if ($name == '') {
        $errors['name'] = 'Het veld: Naam mag niet leeg zijn.';
    }
    if ($date == '') {
        $errors['date'] = 'Het veld: Datum mag niet leeg zijn.';
    }
    if ($email == '') {
        $errors['email'] = 'Het veld: E-mailadres mag niet leeg zijn.';
    }
    if ($message == '') {
        $errors['message'] = 'Het veld: Wat gebeurde er? mag niet leeg zijn.';
    }

    if (empty($errors)) {
      sendMailWhenSomeoneFoundBug($name, $date, $message, $email);
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
initializeHead('..', 'Problemen met reserveren bij Rasa Senang', false, false, false, false);
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', '../');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
<main class="content-wrap">
    <header>
        <h1>Problemen met Reserveren</h1>
    </header>
    <div>
        Bent u tegen problemen aangelopen tijdens het plaatsen van uw reservering, of bent u een foutje tegen gekomen op deze website? <br>
        Wij lossen het graag op!
    </div>
    <br>
    <br>
    <?php if (isset($errorType)) {
        if ($errorType == 'sentSuccessful') { ?>
            <div class="errorLoginPositive">
                <div class="message">
                    Dankuwel, uw bericht is succesvol verstuurd!
                </div>
            </div>
        <?php } else if ($errorType == 'sentUnsuccessful') { ?>
            <div class="errorLoginNegative">
                <div class="message">
                    Helaas is er iets fout gegaan, probeer het later opnieuw.
                </div>
            </div>
        <?php }
    } ?>
    <form action="" method="post">
        <div class="data-field">
            <div class="flexLabel">
                <label for="name" class="loginLabel">Uw naam</label>
                <div class="errors">
                    *
                </div>
            </div>
            <div class="flexInputWithErrors">
                <input type="text" name="name" value="<?= $name ?? '' ?>"/>
                <span class="errors"><?= $errors['name'] ?? '' ?></span>
            </div>
        </div>
        <div class="data-field">
            <div class="flexLabel">
                <label for="date">Op welke datum vond dit plaats?</label>
                <div class="errors">
                    *
                </div>
                <div class="tooltip"><img src="../data/icon-general/information.png" alt="i"> <span
                            class="tooltipText">Als deze onbekend is, vult u gewoon die van vandaag in.</span>
                </div>
            </div>
            <div class="flexInputWithErrors">
                <input type="date" name="date" value="<?= $date ?? '' ?>"/>
                <span class="errors"><?= $errors['date'] ?? '' ?></span>
            </div>
        </div>
        <div class="data-field">
            <div class="flexLabel">
                <label for="name" class="loginLabel">Uw E-mailadres</label>
                <div class="errors">
                    *
                </div>
            </div>
            <div class="flexInputWithErrors">
                <input type="text" name="email" value="<?= $email ?? '' ?>"/>
                <span class="errors"><?= $errors['email'] ?? '' ?></span>
            </div>
        </div>
        <div class="data-field">
            <div class="flexLabel">
                <label for="message">Wat gebeurde er?</label>
                <div class="errors">
                    *
                </div>
                <div class="tooltip"><img src="../data/icon-general/information.png" alt="i"> <span
                            class="tooltipText">Probeer te beschrijven wat u deed, wat er fout ging of waar de fout te vinden is.</span>
                </div>
            </div>
            <textarea name="message" cols="40" rows="5"><?php if (isset($message) && $message !== '') {
                    echo htmlspecialchars_decode($message);
                } ?></textarea>
            <span class="errors"><?= $errors['message'] ?? '' ?></span>
        </div>
        <div class="data-submit">
            <input type="submit" name="submit" value="Versturen"/>
        </div>
    </form>
</main>
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>
