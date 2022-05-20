<?php
session_start();

$changed = false;
$deleted = false;

//Require database in this file
require_once '../includes/database.php';
require_once '../mailer.php';
/** @var mysqli $db */

if (!isset($_SESSION['reservation']) && !isset($_SESSION['canChangeReservation'])) {
    header("Location: ../");
    exit;
} else {
    if (isset($_SESSION['deletedReservation'])) {
        $deleted = true;

        $reservationIdMail = $_SESSION['canChangeReservation']['reservering_id'];
        $randomNumberMail = $_SESSION['reservation']['random-number'];
        $nameMail = $_SESSION['reservation']['name'];
        $dateMail = date('d/m/Y', strtotime($_SESSION['reservation']['date']));
        $timeMail = date('H:i', strtotime($_SESSION['reservation']['time']));
        $amountMail = $_SESSION['reservation']['people'];
        $phoneMail = $_SESSION['reservation']['phonenumber'];
        $addressMail = $_SESSION['reservation']['emailadres'];
        $allergiesMail = $_SESSION['reservation']['str_all'];
        if ($_SESSION['reservation']['comments'] == '') {
            $commentsMail = 'Niet van toepassing.';
        } else {
            $commentsMail = $_SESSION['reservation']['comments'];
        }
        sendMail('deleted', $reservationIdMail, $randomNumberMail, $nameMail, $dateMail, $timeMail, $amountMail, $phoneMail, $allergiesMail, $commentsMail, $addressMail);
        unset($_SESSION['canChangeReservation']);
        unset($_SESSION['deletedReservation']);
        unset($_SESSION['reservation']);
    } else if (isset($_SESSION['canChangeReservation'])) {
        $whatsappDate = date('d/m/Y', strtotime($_SESSION['reservation']['date']));
        $whatsappTime = date('H:i', strtotime($_SESSION['reservation']['time']));
        $whatsappPeopleAmount = mysqli_escape_string($db, $_SESSION['reservation']['people']);

        $reservationIdMail = $_SESSION['canChangeReservation']['reservering_id'];
        $randomNumberMail = $_SESSION['reservation']['random-number'];
        $nameMail = $_SESSION['reservation']['name'];
        $dateMail = date('d/m/Y', strtotime($_SESSION['reservation']['date']));
        $timeMail = date('H:i', strtotime($_SESSION['reservation']['time']));
        $amountMail = $_SESSION['reservation']['people'];
        $phoneMail = $_SESSION['reservation']['phonenumber'];
        $addressMail = $_SESSION['reservation']['emailadres'];
        $allergiesMail = $_SESSION['reservation']['str_all'];
        if ($_SESSION['reservation']['comments'] == '') {
            $commentsMail = 'Niet van toepassing.';
        } else {
            $commentsMail = $_SESSION['reservation']['comments'];
        }
        sendMail('changed', $reservationIdMail, $randomNumberMail, $nameMail, $dateMail, $timeMail, $amountMail, $phoneMail, $allergiesMail, $commentsMail, $addressMail);

        unset($_SESSION['reservation']);
        unset($_SESSION['canChangeReservation']);
        $changed = true;
    } else {
        $whatsappDate = date('d/m/Y', strtotime($_SESSION['reservation']['date']));
        $whatsappTime = date('H:i', strtotime($_SESSION['reservation']['time']));
        $whatsappPeopleAmount = mysqli_escape_string($db, $_SESSION['reservation']['people']);

        $reservationIdMail = $_SESSION['reservation']['reservering_id'];
        $randomNumberMail = $_SESSION['reservation']['random-number'];
        $nameMail = $_SESSION['reservation']['name'];
        $dateMail = date('d/m/Y', strtotime($_SESSION['reservation']['date']));
        $timeMail = date('H:i', strtotime($_SESSION['reservation']['time']));
        $amountMail = $_SESSION['reservation']['people'];
        $phoneMail = $_SESSION['reservation']['phonenumber'];
        $addressMail = $_SESSION['reservation']['emailadres'];
        $allergiesMail = $_SESSION['reservation']['str_all'];
        if ($_SESSION['reservation']['comments'] == '') {
            $commentsMail = 'Niet van toepassing.';
        } else {
            $commentsMail = $_SESSION['reservation']['comments'];
        }
        sendMail('new', $reservationIdMail, $randomNumberMail, $nameMail, $dateMail, $timeMail, $amountMail, $phoneMail, $allergiesMail, $commentsMail, $addressMail);
        unset($_SESSION['reservation']);
    }
    mysqli_close($db);
}



//include basic pages such as navbar and header
require_once "../includes/basic-elements/head.php";
initializeHead('..', 'Bevestiging van reservering bij Rasa Senang', false, false, false, false);
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', '../');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
    <main class="content-wrap">
        <div class="confirmation-page">
            <?php if ($deleted == true) { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/bin.png">
                    <h1>Uw reservering is succesvol verwijderd!</h1>
                    <h3>Let op: Dit kan niemand meer ongedaan maken.</h3>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail. Ziet u deze niet? Controleer a.u.b. uw spam.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                </section>
            <?php } elseif ($changed == true) { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/check.png">
                    <h1>Uw reservering is succesvol gewijzigd!</h1>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail. Ziet u deze niet? Controleer a.u.b. uw spam.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                    <a class="inlineBlockWhatsapp"
                       href="whatsapp://send?text=Hoi, ik heb zojuist gereserveerd bij Rasa Senang! Ik heb gereserveerd op: <?= $whatsappDate ?>, vanaf: <?= $whatsappTime ?>, voor <?= $whatsappPeopleAmount ?> personen. Dit is het adres van het restaurant: De Jagerweg 227; 3328 AA, Dordrecht. Ik heb er veel zin in!"
                       data-action="share/whatsapp/share"
                       target="_blank">
                        <button class="whatsappShareButton">
                            <img src="../data/icon-general/WhatsApp_icon.png">
                            <div>Delen via WhatsApp</div>
                        </button>
                    </a>
                </section>
            <?php } else { ?>
                <section>
                    <img class="thanks-img" src="../data/icon-general/heart.png">
                    <h1>Bedankt voor uw reservering!</h1>
                    <h3>U krijgt binnen 15 minuten een bevestigingsmail. Ziet u deze niet? Controleer a.u.b. uw spam.</h3>
                    <h3>Mocht u nog vragen hebben voor ons, dan helpen wij u graag! Bel gerust naar: 078-6511160.</h3>
                    <a class="inlineBlockWhatsapp"
                       href="whatsapp://send?text=Hoi, ik heb zojuist gereserveerd bij Rasa Senang! Ik heb gereserveerd op: <?= $whatsappDate ?>, vanaf: <?= $whatsappTime ?>, voor <?= $whatsappPeopleAmount ?> personen. Dit is het adres van het restaurant: De Jagerweg 227; 3328 AA, Dordrecht. Ik heb er veel zin in!"
                       data-action="share/whatsapp/share"
                       target="_blank">
                        <button class="whatsappShareButton">
                            <img src="../data/icon-general/WhatsApp_icon.png">
                            <div>Delen via WhatsApp</div>
                        </button>
                    </a>
                </section>
            <?php } ?>
        </div>
    </main>
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>