<?php
session_start();

if (isset($_SESSION['reservation'])) {
    unset($_SESSION['reservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

date_default_timezone_set("Europe/Amsterdam");

$date = date("Y-m-d");

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_reservations');

if (isset($_POST['submit'])) {
    if ($_POST['date'] !== '') {
        $date = date("Y-m-d", strtotime($_POST['date']));
        if (date("Y-m-d", strtotime($date)) < date("Y-m-d", strtotime("2021-12-16"))) {
            $query = "SELECT * FROM reserveringen WHERE `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL ORDER BY `date`";
        } else {
            $query = "SELECT * FROM reserveringen WHERE date = '$date' AND `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL";
        }
    } else {
        $query = "SELECT * FROM reserveringen WHERE `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL ORDER BY `date`";
    }
    //Get the result set from the database with a SQL query
    $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
    //Loop through the result to create a custom array
    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    //Close connection
    mysqli_close($db);
} else {
    $query = "SELECT * FROM `reserveringen` WHERE `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL  ORDER BY `date`";
    //Get the result set from the database with a SQL query
    $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
    //Loop through the result to create a custom array
    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    //Close connection
    mysqli_close($db);
}

//initiate reservation count for later use
$reservationCount = 0;

foreach ($reservations as $reservation) {
    if (date("Y m d", strtotime($reservation['date'])) > date("Y m d") || date("Y m d", strtotime($reservation['date'])) == date("Y m d", strtotime($date))) {
        $reservationCount++;
    }
}

//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Reserveringen van Rasa Senang');
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', '../medewerkers');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Overzicht Reserveringen</h1>
        </header>
        <section class="search-bar-container">
            <div class="search-bar">
                <div class="search-bar-item">
                    <a href="../">
                        <button class="date-submit">
                            Nieuwe Reservering
                        </button>
                    </a>
                </div>
                <div class="search-bar-item">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="date-field">
                            <input class="date-field" id="date" type="date" name="date" value="<?= $date ?? '' ?>"/>
                        </div>
                </div>
                <div class="search-bar-item">
                    <div class="date-submit-div">
                        <input class="date-submit" type="submit" name="submit" value="Zoeken"/>
                    </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="align-middle">
            <?php if (count($reservations) == 0) { ?>
                <p class="middle-table">Er zijn geen reserveringen gevonden op de opgegeven datum.</p>
            <?php } elseif ($reservationCount == 0) { ?>
                <p class="middle-table">Er zijn geen reserveringen gevonden die plaatsvinden op, of
                    na, <?php echo date('d-m-Y') ?>.</p>
            <?php } else { ?>
                <table class="middle-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Datum</th>
                        <th>Aanvangstijd</th>
                        <th>Aantal Gasten</th>
                        <th>Naam</th>
                        <th colspan="3"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservations as $reservation) {
                        if (date("Y m d", strtotime($reservation['date'])) > date("Y m d") || date("Y m d", strtotime($reservation['date'])) == date("Y m d", strtotime($date))) {
                            ?>
                            <tr>
                                <td><?= htmlentities($reservation['reservering_id']) ?></td>
                                <td><?= htmlentities(date("d/m/Y", strtotime($reservation['date']))) ?></td>
                                <td><?= htmlentities(date("H:i", strtotime($reservation['start_time']))) ?></td>
                                <td><?= htmlentities($reservation['amount_people']) ?></td>
                                <td><?= htmlentities($reservation['full_name']) ?></td>
                                <td>
                                    <a href="details.php?id=<?= htmlentities($reservation['reservering_id']) ?>"><img
                                                class="details-button" src="../data/icon-general/information.png"
                                                alt="Details"></a>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
            <?php } ?>
        </section>
    </main>
    <?php require_once('../includes/footer.php');
    oneDotOrMoreFooter('..'); ?>
</div>
</body>
</html>
