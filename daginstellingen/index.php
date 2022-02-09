<?php
session_start();


//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/logincheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');

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

$date = date("Y-m-d");

$deleteReservations = [];

//if the reservation is set to be deleted (the confirmation mail has been sent) put it in the deletion array
//else if the delete mail is null then add it to the day-summary
foreach ($reservations as $reservation) {
    if ($reservation['delete_mail_sent'] == "true") {
        $deleteReservations[] = $reservation['reservering_id'];
    } elseif ($reservation['delete_mail_sent'] == '') {
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
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <title>Daginstellingen van Rasa Senang</title>
</head>


<body>

<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="../medewerkers">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Beginpagina">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Daginstellingen</h1>
        </header>
        <div class="search-bar">
            <button class="date-submit">
                <a href="../daginstellingen/rules.php">
                    Regels overzicht
                </a>
            </button>
        </div>
        <div class="flexDaySettings">
            <div class="daySummary">
                <h2>Instellingen voor Vandaag:</h2>
                <div class="flexDetails">
                    <div class="labelDetails">Restaurant open/dicht:</div>
                    <div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Gasten limiet:</div>
                    <div><?php //guestlimittoday ?>(Er komen vandaag <?= $guestCount; ?> gasten)</div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Reservering limiet:</div>
                    <div><?php //reservationslimittoday ?>(Er zijn vandaag <?= $amountReservations;; ?>reserveringen)
                    </div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Beschikbare tijden:</div>
                    <div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Beschikbare tijdsloten:</div>
                    <div></div>
                </div>
            </div>
            <div class="daySummary">
                <h2>Algemene Instellingen:</h2>
                <div class="flexDetails">
                    <div class="labelDetails">Restaurant open/dicht:</div>
                    <div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Gasten limiet:</div>
                    <div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Reservering limiet:</div>
                    <div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Beschikbare tijden:</div>
                    <div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Beschikbare tijdsloten:</div>
                    <div></div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>