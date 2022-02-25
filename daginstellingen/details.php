<?php
session_start();

if (isset($_SESSION['reservation'])) {
    unset($_SESSION['reservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

// redirect when uri does not contain a id
if (!isset($_GET['id']) || $_GET['id'] == '') {
    // redirect to index.php
    header('Location: ./regels.php');
    exit;
}

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

//Retrieve the GET parameter from the 'Super global'
$reservationID = mysqli_escape_string($db, $_GET['id']);

//Get the record from the database result
$query = "SELECT * FROM reserveringen WHERE reservering_id = '$reservationID'";
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query)
if (mysqli_num_rows($result) !== 1) {
    // redirect when db returns no result
    header('Location: ./');
    exit;
}

$reservation = mysqli_fetch_assoc($result);


$emailGuestInfo = mysqli_escape_string($db, $reservation['emailadres']);
$queryGuestInfo = "SELECT COUNT(emailadres) FROM reserveringen WHERE emailadres = '$emailGuestInfo' AND `deleted_by_user` IS NULL";
$resultGuestInfo = mysqli_query($db, $queryGuestInfo); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
//Loop through the result to create a custom array
$reservationsGuestInfo = mysqli_fetch_assoc($resultGuestInfo);

mysqli_close($db);

$amountReservationsGuestInfo = $reservationsGuestInfo['COUNT(emailadres)'];

if ($amountReservationsGuestInfo == 1) {
    $guestInfoMessage = "(Nieuwe Gast: 1 reservering)";
} elseif ($amountReservationsGuestInfo > 1 && $amountReservationsGuestInfo <= 10) {
    $guestInfoMessage = "(Terugkerende Gast: " . $amountReservationsGuestInfo . " reserveringen)";
} else {
    $guestInfoMessage = "(Trouwe Gast: " . $amountReservationsGuestInfo . " reserveringen)";
}


if (isset($_POST['change'])) {
    $_SESSION['canChangeReservation'] = [
        'reservering_id' => $reservationID,
    ];
    $reservering_id = mysqli_escape_string($db, $_SESSION['canChangeReservation']['reservering_id']);
    $queryChange = "SELECT * FROM reserveringen WHERE reservering_id = '$reservering_id'";
    $resultChange = mysqli_query($db, $queryChange); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
    $reservation = mysqli_fetch_assoc($resultChange);

    mysqli_close($db);

    $_SESSION['canChangeReservation']['date'] = test_input($reservation['date']);
    $_SESSION['canChangeReservation']['start_time'] = test_input(date("H:i", strtotime($reservation['start_time'])));
    $_SESSION['canChangeReservation']['amount_people'] = test_input($reservation['amount_people']);
    $_SESSION['canChangeReservation']['full_name'] = test_input($reservation['full_name']);
    $_SESSION['canChangeReservation']['emailadres'] = test_input($reservation['emailadres']);
    $_SESSION['canChangeReservation']['phonenumber'] = test_input($reservation['phonenumber']);
    $_SESSION['canChangeReservation']['comments'] = test_input($reservation['comments']);

    $_SESSION['canChangeReservation']['all_egg'] = $reservation['all_egg'];
    $_SESSION['canChangeReservation']['all_gluten'] = $reservation['all_gluten'];
    $_SESSION['canChangeReservation']['all_lupine'] = $reservation['all_lupine'];
    $_SESSION['canChangeReservation']['all_milk'] = $reservation['all_milk'];
    $_SESSION['canChangeReservation']['all_mustard'] = $reservation['all_mustard'];
    $_SESSION['canChangeReservation']['all_nuts'] = $reservation['all_nuts'];
    $_SESSION['canChangeReservation']['all_peanut'] = $reservation['all_peanut'];
    $_SESSION['canChangeReservation']['all_shell'] = $reservation['all_shell'];
    $_SESSION['canChangeReservation']['all_celery'] = $reservation['all_celery'];
    $_SESSION['canChangeReservation']['all_sesame'] = $reservation['all_sesame'];
    $_SESSION['canChangeReservation']['all_soja'] = $reservation['all_soja'];
    $_SESSION['canChangeReservation']['all_fish'] = $reservation['all_fish'];
    $_SESSION['canChangeReservation']['all_mollusks'] = $reservation['all_mollusks'];
    $_SESSION['canChangeReservation']['all_sulfur'] = $reservation['all_sulfur'];

    $date = $_SESSION['canChangeReservation']['date'];
    $time = $_SESSION['canChangeReservation']['start_time'];
    $people = $_SESSION['canChangeReservation']['amount_people'];
    $name = $_SESSION['canChangeReservation']['full_name'];
    $emailadres = $_SESSION['canChangeReservation']['emailadres'];
    $phonenumber = $_SESSION['canChangeReservation']['phonenumber'];
    $comments = $_SESSION['canChangeReservation']['comments'];
    $allergie_string = $reservation['str_all'];

    $_SESSION['canChangeReservation']['time'] = $time;
    $_SESSION['canChangeReservation']['people'] = $people;
    $_SESSION['canChangeReservation']['name'] = $name;
    header('Location: ../index.php?edit=1');
    exit;
}


if (isset($_POST['submitDelete'])) {
    if (isset($_POST['reason']) && $_POST['reason'] !== '') {
        $reservationIdQuery = mysqli_escape_string($db, $reservation['reservering_id']);
        $reason = mysqli_escape_string($db, $_POST['reason']);
        $currentTime = date("Y-m-d H:i:s");
        $userDelete = "employee";
        $deleteMail = "false";
        $deleteQuery = "UPDATE reserveringen SET  date_updated_reservation = '$currentTime', deleted_by_user = '$userDelete', reason_of_deletion = '$reason', delete_mail_sent = '$deleteMail' WHERE reservering_id = '$reservationIdQuery'";
        $resultDelete = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
        mysqli_close($db);
        if ($resultDelete) {
            header('Location: ./');
            exit;
        } else {
            header('Location: ./details.php?id=' . $reservation['reservering_id'] . '&error=dbError#open');
            exit;
        }
    } else {
        header('Location: ./details.php?id=' . $reservation['reservering_id'] . '&error=noReason#open');
        exit;
    }
}

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Reservering ' . htmlentities($reservation['reservering_id']) . ' bij Rasa Senang');
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', './');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

<div class="overlaymodal"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Details</h1>
            <h3><?= ' Reserveringsnummer ' . htmlentities($reservation['reservering_id']) ?></h3>
        </header>

        <div class="details">
            <div class="flexDetails">
                <div class="labelDetails">Reservering geplaatst op:</div>
                <div><?= date("d/m/Y - H:i", strtotime($reservation['date_placed_reservation'])) ?></div>
            </div>
            <?php if ($reservation['date_placed_reservation'] !== $reservation['date_updated_reservation']) { ?>
                <div class="flexDetails">
                    <div class="labelDetails"> Reservering laatst gewijzigd op:</div>
                    <div><?= date("d/m/Y - H:i", strtotime($reservation['date_updated_reservation'])); ?></div>
                </div> <?php } ?>
            <div class="flexDetails">
                <div class="labelDetails">Datum:</div>
                <div> <?= date("d/m/Y", strtotime($reservation['date'])) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Aanvangstijd:</div>
                <div> <?= htmlentities(date("H:i", strtotime($reservation['start_time']))) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Aantal gasten:</div>
                <div> <?= htmlentities($reservation['amount_people']) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Naam:</div>
                <div> <?= htmlentities($reservation['full_name']) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">E-mailadres:</div>
                <div class="flexDetailsEmail">
                    <div> <?= htmlentities($reservation['emailadres']) ?></div>
                    <div class="guestLoyaltyIndicator"> <?= $guestInfoMessage ?></div>
                </div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Telefoonnummer:</div>
                <div> <?= htmlentities($reservation['phonenumber']) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Allergieën:</div>
                <div><?= htmlentities($reservation['str_all']) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Opmerkingen:</div>
                <div><?php if ($reservation['comments'] == '') {
                        echo "Niet van toepassing.";
                    } else {
                        echo htmlentities(htmlspecialchars_decode($reservation['comments']));
                    } ?></div>
            </div>
        </div>
        <div class="detailsPageButtons">
            <div class="flexButtons">
                <form action="" method="post">
                    <input class="date-submit" type="submit" name="change" value="Wijzigen"/>
                </form>
                <button class="date-submit" type="button" data-modal-target="#modal">Verwijderen</button>
            </div>
        </div>
        <div class="modal" id="modal" <?php if (isset($_GET['error']) && $_GET['error'] !== '') {?>style="transition: none" <?php }?>>
            <div class="modal-header">
                <div class="title"> Weet u zeker dat u deze reservering wilt verwijderen?</div>
                <button data-close-button class="close-button">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modalAlignCenter">
                    <img src="../data/icon-general/bin-red.png">
                </div>
                <div class="modalAlignCenter">
                    <p class="errors"> <?php if (isset($_GET['error']) && $_GET['error'] !== '') {
                            if ($_GET['error'] == 'dbError') {
                                echo "Er is helaas iets fout gegaan, probeer het later opnieuw.";
                            } elseif ($_GET['error'] == 'noReason') {
                                echo "Het veld: Reden is verplicht.";
                            } else {
                                echo "Let op: deze actie is permanent!";
                            }
                        } else {
                            echo "Let op: deze actie is permanent!";
                        } ?></p>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $reservationID; ?>"
                      method="post">
                    <div class="modalAlignCenter">
                        <div class="modalAlignCenterDeletionReason">
                            <div class="flexLabel">
                                <label for="reason">Reden:</label>
                                <div class="errors">
                                    *
                                </div>
                            </div>
                            <input class="delete-reason" type="text" name="reason" value=""/>
                        </div>
                    </div>
                    <div class="modalAlignCenter">
                        <div class="date-submit-div">
                            <input class="date-submit" type="submit" name="submitDelete" value="Verwijderen"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>