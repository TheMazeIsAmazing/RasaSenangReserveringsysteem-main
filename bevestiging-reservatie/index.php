<?php
session_start();

$changed = false;
$deleted = false;

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');

//May I even visit this page?
if (!isset($_SESSION['reservation'])) {
    if (!isset($_SESSION['canChangeReservation'])) {
        header("Location: ../");
        exit;
    }
    header("Location: ../");
    exit;
}

unset($_SESSION['reservation']);


if (isset($_SESSION['deletedReservation'])) {
    $deleted = true;
    unset($_SESSION['canChangeReservation']);
    unset($_SESSION['deletedReservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    $changed = true;
    unset($_SESSION['canChangeReservation']);
}


if (isset($_SESSION['loggedInUser'])) {
    header("Location: ../overzicht-reserveringen");
    exit;
}

?>

<!doctype html>
<html lang="nl">
<head>
    <title>Bevestiging van Rasa Senang</title>
</head>
<body>
<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="../">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Reserveringen">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
    <main class="content-wrap">
        <div class="confirmation-page">
            <?php if ($deleted == true) { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/bin.png">
                    <h1>Uw reservering is succesvol verwijderd!</h1>
                    <h3>Let op: Dit kan niemand meer ongedaan maken.</h3>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                </section>
            <?php } elseif ($changed == true) { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/check.png">
                    <h1>Uw reservering is succesvol gewijzigd!</h1>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                </section>
            <?php } else { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/heart.png">
                    <h1>Bedankt voor uw reservering!</h1>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                </section>
            <?php } ?>
        </div>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
