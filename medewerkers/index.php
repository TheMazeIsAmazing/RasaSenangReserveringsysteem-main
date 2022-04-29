<?php
session_start();

if (isset($_SESSION['reservation'])) {
    unset($_SESSION['reservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

$date = date("Y-m-d");

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();

//Get Name user from session
$name = mysqli_escape_string($db, $_SESSION['loggedInUser']['name']);

$query = "SELECT * FROM reserveringen";
//Get the result set from the database with a SQL query
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
$reservations = [];

$guestCount = 0;
$amountReservations = 0;
$reservationsPlacedToday = 0;
$reservationsWithAllergies = [];

while ($row = mysqli_fetch_assoc($result)) {
    $reservations[] = $row;
}

$deleteReservations = [];

//if the reservation is set to be deleted (the confirmation mail has been sent) put it in the deletion array
//else if the delete mail is null then add it to the day-summary
foreach ($reservations as $reservation) {
    if ($reservation['delete_mail_sent'] == 'true') {
        $deleteReservations[] = $reservation['reservering_id'];
    } elseif ($reservation['delete_mail_sent'] == '') {
        if (strtotime($reservation['date']) <= strtotime('-2 years')) {
            $deleteReservations[] = $reservation['reservering_id'];
        } else {
            if (date("Y-m-d", strtotime($reservation['date'])) == date("Y-m-d", strtotime($date))) {
                $amountReservations++;
                $guestCount += $reservation['amount_people'];
                if ($reservation['str_all'] !== 'Niet van toepassing.') {
                    $reservationsWithAllergies[] = $reservation['reservering_id'];
                }
            }
            if (date("Y-m-d", strtotime($reservation['date_placed_reservation'])) == date("Y-m-d")) {
                $reservationsPlacedToday++;
            }
        }
    }

}
if (isset($deleteReservations)) {
    foreach ($deleteReservations as $deleteReservation) {
        $deleteQuery = "DELETE FROM reserveringen WHERE reservering_id = '$deleteReservation'";
        mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
    }
}

//function to find hour and display correct message based on it.

//morning 6:00 - 12:00
//afternoon 12:00 - 18:00
//evening 18:00 - 22:00
//night 22:00 - 6:00

if (date('H') >= 06 && date('H') <= 11) {
    $dayPart = "Goedemorgen, ";
} elseif (date('H') <= 17 && date('H') > 11) {
    $dayPart = "Goedemiddag, ";
} elseif (date('H') <= 21 && date('H') > 17) {
    $dayPart = "Goedenavond, ";
} else {
    $dayPart = "Goedenacht, ";
}

$settings = [];
$daysMatchQuery = 0;

$queryDaySettings = "SELECT * FROM `day-settings`";
//Get the result set from the database with a SQL query
$resultDaySettings = mysqli_query($db, $queryDaySettings); //or die('Error: ' . mysqli_error($db) . ' with query ' . $queryDaySettings);

while ($row = mysqli_fetch_assoc($resultDaySettings)) {
    $settings[] = $row;
}
mysqli_close($db);

$restaurantClosed = 'true';
foreach ($settings as $setting) {
    if ((strtotime($setting['until_date']) >= strtotime($date)) && (strtotime($setting['from_date']) <= strtotime($date))) {
        $daysMatchQuery++;
        if ($setting[strtolower(date('l'))] == 'open' && $setting['open_closed'] == 'open') {
            $restaurantClosed = 'false';
            $guestLimit = $setting['guest_limit'];
            $reservationLimit = $setting['reservations_limit'];
        }
    }
}
if ($daysMatchQuery == 0) {
    foreach ($settings as $setting) {
        if ($setting['type'] == 'general') {
            if ($setting[strtolower(date('l'))] == 'open' && $setting['open_closed'] == 'open') {
                $restaurantClosed = 'false';
                $guestLimit = $setting['guest_limit'];
                $reservationLimit = $setting['reservations_limit'];
            }
        }
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
oneDotOrMoreHead('..', 'Welkom ' . htmlentities($name) . ' bij Rasa Senang', false, false, false);
require_once "../includes/basic-elements/topBar.php";
oneDotOrMoreTopBar('..', '../inloggen/logout.php');
require_once "../includes/basic-elements/sideNav.php";
oneDotOrMoreNav('..', false);
?>
    <main class="content-wrap">
        <header>
            <h1> <?= $dayPart . htmlentities($name) ?>!</h1>
        </header>
        <nav class="navEmployees">
            <a class="navEmployeesButton" href="../">Nieuwe Reservering</a>
<?php if ($_SESSION['loggedInUser']['can_visit_reservations'] == 'true') { ?>
            <a class="navEmployeesButton" href="../overzicht-reserveringen">Overzicht Reserveringen</a>
<?php } ?>
<?php if ($_SESSION['loggedInUser']['can_visit_daysettings'] == 'true') { ?>
    <a class="navEmployeesButton" href="../daginstellingen">Daginstellingen</a>
<?php } ?>
                <?php
                /*                 <a class="navEmployeesButton" href="./">Tafelindeling</a>
                <a class="navEmployeesButton" href="./">Statistieken</a>
                <a class="navEmployeesButton" href="./">Logboeken</a> */ ?>
                <?php if ($_SESSION['loggedInUser']['can_visit_employees'] == 'true') { ?>
                    <a class="navEmployeesButton" href="../medewerkers-instellingen">Medewerkers</a>
                <?php } else { ?>
                    <a class="navEmployeesButton"
                       href="../medewerkers-instellingen/details.php?id=<?= htmlentities($_SESSION['loggedInUser']['id']) ?>">Mijn
                        Account</a>
                <?php } ?>
        </nav>
        <div class="daySummary">
            <h2>Dagsamenvatting</h2>
            <?php if ($restaurantClosed === 'false') {?>
            <div class="flexDetails">
                <div class="labelDetails">Aantal Gasten:</div>
                <div><?= $guestCount?></div>
                <div>(Er is vandaag een limiet van <?= $guestLimit; ?> gasten)</div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Aantal Reserveringen:</div>
                <div><?= $amountReservations; ?></div>
                <div>(Er is vandaag een limiet van <?= $reservationLimit; ?> reserveringen)</div>
            </div>
            <?php //in the future display how many tables are occupied
            //<div class="daySummaryItem">
            //Tafels Bezet:
            //</div>?>
            <div class="flexDetails">
                <div class="labelDetails">Gasten met
                    Allergieën:
                </div>
                <div><?php if (count($reservationsWithAllergies) > 1) { ?>
                        Ja bij reserveringen: <?php
                        for ($i = 0; $i < count($reservationsWithAllergies); $i++) {
                            if (($i + 1 == count($reservationsWithAllergies)) && count($reservationsWithAllergies) !== 1) {?>
                                en
                                <a href="../overzicht-reserveringen/details.php?id=<?= $reservationsWithAllergies[$i] ?>"><?= $reservationsWithAllergies[$i] ?></a>
                                <?php
                            } elseif ($i + 2 !== count($reservationsWithAllergies)) {
                                ?>
                                <a href="../overzicht-reserveringen/details.php?id=<?= $reservationsWithAllergies[$i] ?>"><?= $reservationsWithAllergies[$i] ?></a>,
                                <?php
                            } else {?>
                    <a href="../overzicht-reserveringen/details.php?id=<?= $reservationsWithAllergies[$i] ?>"><?= $reservationsWithAllergies[$i] ?></a>
                    <?php
                        }
                    } } else if (count($reservationsWithAllergies) == 1) {
                        for ($i = 0; $i < count($reservationsWithAllergies); $i++) { ?>
                            Ja bij reservering: <a
                                    href="../overzicht-reserveringen/details.php?id=<?= $reservationsWithAllergies[$i] ?>"><?= $reservationsWithAllergies[$i] ?></a>
                        <?php } ?>
                    <?php } else { ?>
                        Niet van toepassing.
                    <?php } ?>
                </div>
            </div>
            <?php if ($_SESSION['loggedInUser']['is-admin'] == 'true') { ?>
            <div class="flexDetails" id="reservationsPlacedToday">
                <div class="labelDetails">Aantal Reserveringen vandaag geplaatst:</div>
                <div><?= $reservationsPlacedToday; ?></div>
            </div>
            <?php } ?>
            <?php } else { ?>
                <div class="flexDetails">
                    <div>Restaurant Gesloten.</div>
                </div>
            <?php } ?>
        </div>
    </main>
    <?php require_once('../includes/basic-elements/footer.php');
    oneDotOrMoreFooter('..'); ?>