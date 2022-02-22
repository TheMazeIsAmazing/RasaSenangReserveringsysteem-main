<?php
session_start();


//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db*/

//May I even visit this page?
require_once "../includes/logincheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

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

mysqli_close($db);

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
        mysqli_close($db);
    }
}

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Daginstellingen van Rasa Senang');
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', '../medewerkers');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

<div class="overlay"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Daginstellingen</h1>
        </header>
        <div class="search-bar">
            <a href="../daginstellingen/regels.php">
            <button class="date-submit">
                    Regels overzicht
            </button>
            </a>
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
                    <div><?php //reservationslimittoday ?>(Er zijn vandaag <?= $amountReservations;; ?> reserveringen)
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