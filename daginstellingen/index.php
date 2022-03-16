<?php
session_start();


//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db*/

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');



$queryDaySettings = "SELECT * FROM `day-settings`";
//Get the result set from the database with a SQL query
$resultDaySettings = mysqli_query($db, $queryDaySettings); //or die('Error: ' . mysqli_error($db) . ' with query ' . $queryDaySettings);

$date = date('Y-m-d');

$queryReservations = "SELECT * FROM reserveringen WHERE date = '$date' AND `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL";
$resultReservations = mysqli_query($db, $queryReservations) or die('Error: ' . mysqli_error($db) . ' with query ' . $queryReservations);
$reservations = [];

while ($row = mysqli_fetch_assoc($resultReservations)) {
    $reservations[] = $row;
}

$guestCount = 0;
$amountReservations = 0;

foreach ($reservations as $reservation) {
    $amountReservations++;
    $guestCount += $reservation['amount_people'];
}

mysqli_close($db);



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
                    <div><?php //reservationslimittoday ?>(Er zijn vandaag <?= $amountReservations; ?> reserveringen)
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