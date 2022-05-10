<?php
function initializeSideNav($dotsString, $topArrow)
{
$link = '/';
if ($_SERVER['PHP_SELF'] !== 'index.php' && $_SERVER['PHP_SELF'] !== '/index.php') {
    $linkSplit = explode("/", $_SERVER['PHP_SELF']);
    foreach ($linkSplit as $part) {
        if ($part !== 'RasaSenangReserveringsysteem-main' && $part !== '1028473') {
            if ($link == '/') {
                $link = $link . $part;
            } else {
                $link = $link . '/'. $part;
            }
        }
    }
} elseif ($_SERVER['PHP_SELF'] == '/index.php') {
    $link = $_SERVER['PHP_SELF'];
}
?>

<nav class="sideNav">
    <button class="menuX">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <?php if (!isset($_SESSION['loggedInUser'])) {?>
        <div class="content-wrap">
            <div class="page-container">
                <ul>
                    <li><a class="menuLink" href="https://www.rasasenang.com/nl/">Terug naar Rasa Senang Website</a></li>
                    <li><a <?php if (($link == '/index.php' && !isset($_GET['edit'])) || (($link == '/controleren-reservatie/index.php' || $link == '/bevestiging-reservatie/index.php') && !isset($_SESSION['canChangeReservation']))) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/">Nieuwe Reservering</a></li>
                    <li><a <?php if ($link == '/wijzigen-reservering/index.php' || ($link  == '/controleren-reservatie/index.php' && isset($_SESSION['canChangeReservation'])) || ($link  == '/index.php' && isset($_GET['edit'])) || (($link == '/controleren-reservatie/index.php' || $link == '/bevestiging-reservatie/index.php') && isset($_SESSION['canChangeReservation']))) { ?> id="menuLinkActive" <?php }?>class="menuLink" href="<?= $dotsString ?>/wijzigen-reservering">Reservering Wijzigen</a></li>
                </ul>
            </div>
            <ul>
                <li><a <?php if ($link  == '/inloggen/index.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/inloggen">Inloggen Medewerkers</a></li>
            </ul>
        </div>
    <?php } else { ?>
        <div class="content-wrap">
            <div class="page-container">
                <ul>
                    <li><a <?php if ($link == '/medewerkers/index.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/medewerkers">Beginpagina</a></li>
                    <li><a <?php if ($link == '/overzicht-reserveringen/index.php' || $link == '/overzicht-reserveringen/details.php' || ($link == '/index.php' && isset($_GET['edit'])) || ($link  == '/controleren-reservatie/index.php' && isset($_SESSION['canChangeReservation']))) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/overzicht-reserveringen">Overzicht Reserveringen</a></li>
                    <li><a <?php if ($link == '/index.php'  && !isset($_GET['edit']) || ($link == '/controleren-reservatie/index.php' && !isset($_SESSION['canChangeReservation']))) { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/">Nieuwe Reservering</a></li>
                    <li><a <?php if ($link == '/daginstellingen/index.php' || $link == '/daginstellingen/regels.php' || $link == '/daginstellingen/details.php' || $link == '/daginstellingen/new-rule.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/daginstellingen">Daginstellingen</a></li>
                    <?php /*
                    <li><a class="menuLink" href="./">Tafelindeling</a></li> */ ?>

                    <li><a <?php if ($link == '/medewerkers-instellingen/index.php' || $link == '/medewerkers-instellingen/details.php' || $link == '/inloggen/nieuwe-gebruiker.php') { ?> id="menuLinkActive" <?php }?> class="menuLink" href="<?= $dotsString ?>/medewerkers-instellingen">Medewerkers</a></li>
                </ul>
            </div>
            <ul>
                <li><a class="menuLink" href="<?= $dotsString ?>/inloggen/logout.php">Uitloggen</a></li>
            </ul>
        </div>
    <?php } ?>
</nav>
    <?php if ($topArrow == true) { ?>
    <div id="top"><i>˄</i></div>
<?php } ?>
<?php if (isset($_SESSION['loggedInUser'])) { ?>
    <div class="page-container" id="employeeTopBar">
<?php } else { ?>
    <div class="page-container" id="customerTopBar">
<?php } }?>