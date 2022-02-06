<!doctype html>
<html lang="nl">


<?php function oneDotOrMoreNav($dotsString)
{ ?>
<nav class="sideNav">
    <button id="menuX" class="menuX">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <?php if (!isset($_SESSION['loggedInUser'])) {?>
        <div class="content-wrap">
            <div class="page-container">
                <ul>
                    <li><a class="menuLink" href="https://www.rasasenang.com/nl/">Terug naar Rasa Senang Website</a></li>
                    <li><a <?php if (($_SERVER['PHP_SELF'] == '/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/index.php') && !isset($_GET['edit']) || (($_SERVER['PHP_SELF'] == '/controleren-reservatie/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/controleren-reservatie/index.php' ) && !isset($_SESSION['canChangeReservation']))) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/">Nieuwe Reservering</a></li>
                    <li><a <?php if ($_SERVER['PHP_SELF'] == '/wijzigen-reservering/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/wijzigen-reservering/index.php ' || (($_SERVER['PHP_SELF'] == '/controleren-reservatie/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/controleren-reservatie/index.php' ) && isset($_SESSION['canChangeReservation'])) || ($_SERVER['PHP_SELF'] == '/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/index.php') && isset($_GET['edit'])) { ?> id="menuLinkActive" <?php }?>class="menuLink" href="<?= $dotsString ?>/wijzigen-reservering">Reservering Wijzigen</a></li>
                </ul>
            </div>
            <ul>
                <li><a <?php if ($_SERVER['PHP_SELF'] == '/inloggen/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/inloggen/index.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/inloggen">Inloggen Medewerkers</a></li>
            </ul>
        </div>
    <?php } else { ?>
        <div class="content-wrap">
            <div class="page-container">
                <ul>
                    <li><a  <?php if ($_SERVER['PHP_SELF'] == '/medewerkers/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/medewerkers/index.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/medewerkers">Beginpagina</a></li>
                    <li><a <?php if ($_SERVER['PHP_SELF'] == '/overzicht-reserveringen/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/overzicht-reserveringen/index.php' || $_SERVER['PHP_SELF'] == '/overzicht-reserveringen/details.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/overzicht-reserveringen/details.php' || ($_SERVER['PHP_SELF'] == '/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/index.php') && isset($_GET['edit'])) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/overzicht-reserveringen">Overzicht
                            Reserveringen</a></li>
                    <li><a <?php if (($_SERVER['PHP_SELF'] == '/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/index.php') && !isset($_GET['edit'])) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/">Nieuwe Reservering</a></li>
                    <li><a <?php if (($_SERVER['PHP_SELF'] == '/daginstellingen/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/daginstellingen/index.php') && !isset($_GET['edit'])) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/daginstellingen">Daginstellingen</a></li>
                    <?php /*
                    <li><a class="menuLink" href="./">Tafelindeling</a></li>
 <li><a class="menuLink" href="./">Statistieken</a></li>
  <li><a class="menuLink" href="./">Logboeken</a></li> */ ?>

                    <li><a <?php if ($_SERVER['PHP_SELF'] == '/medewerkers-instellingen/index.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/medewerkers-instellingen/index.php' || $_SERVER['PHP_SELF'] == '/medewerkers-instellingen/details.php' || $_SERVER['PHP_SELF'] == '/RasaSenangReserveringsysteem-main/medewerkers-instellingen/details.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/medewerkers-instellingen">Medewerkers</a></li>
            </div>
            <ul>
                <li><a class="menuLink" href="<?= $dotsString ?>/inloggen/logout.php">Uitloggen</a></li>
            </ul>
        </div>

    <?php }
    ?></nav> <?php
} ?>