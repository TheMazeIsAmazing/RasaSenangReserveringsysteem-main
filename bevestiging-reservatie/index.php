<?php
session_start();

$changed = false;
$deleted = false;

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?

if (isset($_SESSION['loggedInUser'])) {
    header("Location: ../overzicht-reserveringen");
    exit;
}

if (!isset($_SESSION['reservation']) && !isset($_SESSION['canChangeReservation'])) {
    header("Location: ../");
    exit;
} else {
    if (isset($_SESSION['deletedReservation'])) {
        $deleted = true;
        unset($_SESSION['canChangeReservation']);
        unset($_SESSION['deletedReservation']);
        unset($_SESSION['reservation']);
    } elseif (isset($_SESSION['canChangeReservation'])) {
        $changed = true;
        $whatsappDate = date('d F Y', strtotime($_SESSION['canChangeReservation']['date']));
        $whatsappTime = date('H:i', strtotime($_SESSION['canChangeReservation']['time']));
        $whatsappPeopleAmount = mysqli_escape_string($db, $_SESSION['canChangeReservation']['amount_people']);
        unset($_SESSION['canChangeReservation']);
        unset($_SESSION['reservation']);
    } else {
        $whatsappDate = date('d F Y', strtotime($_SESSION['reservation']['date']));
        $whatsappTime = date('H:i', strtotime($_SESSION['reservation']['time']));
        $whatsappPeopleAmount = mysqli_escape_string($db, $_SESSION['reservation']['people']);
        unset($_SESSION['reservation']);
    }
    mysqli_close($db);
}

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Bevestiging van reservering bij Rasa Senang');
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', '../');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

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
                    <a class="whatsappShareButton"
                       href="whatsapp://send?text=Ik heb zojuist gereserveerd bij Rasa Senang! Ik heb gereserveerd op: <?= $whatsappDate ?>, vanaf: <?= $whatsappTime ?>, voor <?= $whatsappPeopleAmount ?> personen. Dit is het adres van het restaurant: De Jagerweg 227; 3328 AA, Dordrecht"
                       data-action="share/whatsapp/share"
                       target="_blank">
                        <div class="flexWhatsapp"><img src="../data/icon-general/WhatsApp_icon.png">
                            <div>Delen via WhatsApp</div>
                        </div>
                    </a>
                </section>
            <?php } else { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/heart.png">
                    <h1>Bedankt voor uw reservering!</h1>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                    <a class="whatsappShareButton"
                       href="whatsapp://send?text=Ik heb zojuist gereserveerd bij Rasa Senang! Ik heb gereserveerd op: <?= $whatsappDate ?>, vanaf: <?= $whatsappTime ?>, voor <?= $whatsappPeopleAmount ?> personen. Dit is het adres van het restaurant: De Jagerweg 227; 3328 AA, Dordrecht"
                       data-action="share/whatsapp/share"
                       target="_blank">
                        <div class="flexWhatsapp"><img src="../data/icon-general/WhatsApp_icon.png">
                            <div>Delen via WhatsApp</div>
                        </div>
                    </a>
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
