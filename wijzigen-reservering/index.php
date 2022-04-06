<?php
session_start();


if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

if (isset($_POST['submit'])) {
    $reservation = mysqli_escape_string($db, $_POST['reservering_id']);
    $reservering_id = mysqli_escape_string($db, $_POST['reservering_id']);
    $emailadres = mysqli_escape_string($db, $_POST['emailadres']);
    $unique_code = mysqli_escape_string($db, $_POST['unique_code']);

    $errors = [];
    if ($reservering_id == '') {
        $errors['reservering_id'] = 'Het veld: Reserveringsnummer mag niet leeg zijn.';
    }
    if ($emailadres == '') {
        $errors['emailadres'] = 'Het veld: E-mailadres mag niet leeg zijn.';
    }
    if ($unique_code == '') {
        $errors['unique_code'] = 'Het veld: Wijzigingscode mag niet leeg zijn.';
    }

    if (empty($errors)) {
//Get record from DB based on first name
        $query = "SELECT * FROM reserveringen WHERE reservering_id='$reservering_id' AND deleted_by_user IS NULL";
        $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
        if (mysqli_num_rows($result) == 1) {
            $reservation = mysqli_fetch_assoc($result);
            mysqli_close($db);
            if ($emailadres == $reservation['emailadres'] && $unique_code == $reservation['unique_code']) {
                $_SESSION['canChangeReservation'] = [
                    'reservering_id' => $reservering_id,
                    'load_check_page' => "false",
                ];
                header('Location: ../controleren-reservatie/index.php?edit=1');
                exit;
            } else {
                //error onjuiste informatie
                $errors['loginFailed'] = 'De combinatie van Reserveringsnummer, E-mailadres en Wijzigingscode is bij ons niet bekend';
            }
        }
    }
}

//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Wijzigen Reservering bij Rasa Senang', false, false);
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', '../');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..', false);
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Reservering Wijzigen</h1>
            <h3>Vul hieronder de benodigde informatie in zodat wij uw reservering kunnen vinden:</h3>
        </header>
        <form action="" method="post">
            <div class="data-field">
                <div class="flexLabel">
                    <label for="reservering_id">Reserveringsnummer</label>
                    <div class="errors">
                        *
                    </div>
                </div>
                <div class="flexInputWithErrors">
                    <input type="text" name="reservering_id"
                           value="<?= $reservering_id ?? '' ?>"/>
                    <span class="errors"><?= $errors['reservering_id'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="emailadres">E-mailadres</label>
                    <div class="errors">
                        *
                    </div>
                </div>
                <div class="flexInputWithErrors">
                    <input type="email" name="emailadres" maxlength="255"
                           value="<?= $emailadres ?? '' ?>"/>
                    <span class="errors"><?= $errors['emailadres'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="unique_code" class="changeLabel">Wijzigingscode
                    </label>
                    <div class="errors">
                        *
                    </div>
                    <div class="tooltip"><img src="../data/icon-general/information.png"> <span
                                class="tooltiptext">Vul hier uw unieke wijzigingscode in die u heeft ontvangen in uw e-mailbevestiging. Mocht u deze kwijt zijn kunt u voor het wijzigen van uw reservering ook contact opnemen met het restaurant via: 078-6511160. </span>
                    </div>
                </div>
                <div class="flexInputWithErrors">
                    <input type="text" name="unique_code" maxlength="4"
                           value="<?= $unique_code ?? '' ?>"/>
                    <span class="errors"><?= $errors['unique_code'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-submit">
                <p class="errors"><?= $errors['loginFailed'] ?? '' ?></p>
                <input type="submit" name="submit" value="Zoeken"/>
            </div>
        </form>
    </main>
    <?php require_once('../includes/footer.php');
    oneDotOrMoreFooter('..'); ?>
</div>
</body>
</html>
