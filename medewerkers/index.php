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
require_once "../includes/logincheck.php";
loginCheck();

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
    <title>Medewerkers van Rasa Senang</title>
</head>


<body>

<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="../inloggen/logout.php">
        <button class="back">
            <img src="../data/icon-general/log-out.png" alt="Uitloggen">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1> <?= $dayPart . htmlentities($name) ?>!</h1>
        </header>
        <nav class="navEmployees">
            <a class="navEmployeesButton" href="../overzicht-reserveringen">Overzicht Reserveringen</a>
            <a class="navEmployeesButton" href="../">Nieuwe Reservering</a>
            <a class="navEmployeesButton" href="../daginstellingen">Daginstellingen
                <?php
                /*                 <a class="navEmployeesButton" href="./">Tafelindeling</a>
                <a class="navEmployeesButton" href="./">Statistieken</a>
                <a class="navEmployeesButton" href="./">Logboeken</a> */ ?>
                <a class="navEmployeesButton" href="../medewerkers-instellingen">Medewerkers</a>
        </nav>
        <div class="daySummary">
            <h2>Dagsamenvatting</h2>
            <div class="flexDetails">
                <div class="labelDetails">Aantal Gasten:</div>
                <div><?= $guestCount; ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Aantal Reserveringen:</div>
                <div><?= $amountReservations; ?></div>
            </div>
            <!--                <div class="flexDetails">-->
            <!--                    <div class="labelDetails">Aantal Reserveringen vandaag geplaatst:</div>-->
            <!--                    <div>--><? //= $reservationsPlacedToday; ?><!--</div>-->
            <!--                </div>-->
            <?php //in the future display how many tables are occupied
            //<div class="daySummaryItem">
            //Tafels Bezet:
            //</div>?>
            <div class="flexDetails">
                <div class="labelDetails"> Gasten met
                    Allergieën:
                </div>
                <div><?php if (count($reservationsWithAllergies) > 0) {
                        if (count($reservationsWithAllergies) > 1) {
                            $reservationsWithAllergies_string = "Ja, bij reserveringen: ";
                        } else {
                            $reservationsWithAllergies_string = "Ja, bij reservering: ";
                        }

                        for ($i = 0; $i < count($reservationsWithAllergies); $i++) {
                            if (($i + 1 == count($reservationsWithAllergies)) && count($reservationsWithAllergies) !== 1) {
                                $reservationsWithAllergies_string = "$reservationsWithAllergies_string en $reservationsWithAllergies[$i]";
                            } elseif (count($reservationsWithAllergies) == 1 || $i == 0) {
                                $reservationsWithAllergies_string = "$reservationsWithAllergies_string $reservationsWithAllergies[$i]";
                            } else {
                                $reservationsWithAllergies_string = "$reservationsWithAllergies_string, $reservationsWithAllergies[$i]";
                            }
                        }
                        echo $reservationsWithAllergies_string;
                    } else {
                        echo "Niet van toepassing.";
                    } ?>
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