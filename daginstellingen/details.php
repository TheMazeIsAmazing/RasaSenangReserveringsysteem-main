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
$settingID = mysqli_escape_string($db, $_GET['id']);

//Get the record from the database result
$query = "SELECT * FROM `day-settings` WHERE id = '$settingID'";
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
if (mysqli_num_rows($result) !== 1) {
    // redirect when db returns no result
    header('Location: ./');
    exit;
}

$setting = mysqli_fetch_assoc($result);

mysqli_close($db);

$times = [];

if ($setting['16:00'] == 'true') {
    $times[] = '16:00';
}
if ($setting['16:30'] == 'true') {
    $times[] = '16:30';
}
if ($setting['17:00'] == 'true') {
    $times[] = '17:00';
}
if ($setting['17:30'] == 'true') {
    $times[] = '17:30';
}
if ($setting['18:00'] == 'true') {
    $times[] = '18:00';
}
if ($setting['18:30'] == 'true') {
    $times[] = '18:30';
}
if ($setting['19:00'] == 'true') {
    $times[] = '19:00';
}
if ($setting['19:30'] == 'true') {
    $times[] = '19:30';
}
if ($setting['20:00'] == 'true') {
    $times[] = '20:00';
}
if ($setting['20:30'] == 'true') {
    $times[] = '20:30';
}
if ($setting['21:00'] == 'true') {
    $times[] = '21:00';
}

$time_string = "Niet van toepassing";

if (count($times) >= 1) {
    $time_string = "";

    for ($i = 0; $i < count($times); $i++) {
        if (($i + 1 == count($times)) && count($times) !== 1) {
            $time_string = "$time_string en $times[$i]";
        } elseif (count($times) == 1 || $i == 0) {
            $time_string = "$time_string $times[$i]";
        } else {
            $time_string = "$time_string, $times[$i]";
        }
    }
}



//if (isset($_POST['change'])) {
//    $_SESSION['daySettingChange'] = [
//        'setting_id' => $reservationID,
//    ];
//    $reservering_id = mysqli_escape_string($db, $_SESSION['canChangeReservation']['reservering_id']);
//    $queryChange = "SELECT * FROM reserveringen WHERE reservering_id = '$reservering_id'";
//    $resultChange = mysqli_query($db, $queryChange); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
//    $reservation = mysqli_fetch_assoc($resultChange);
//
//    mysqli_close($db);
//
//    $_SESSION['canChangeReservation']['date'] = test_input($reservation['date']);
//    $_SESSION['canChangeReservation']['start_time'] = test_input(date("H:i", strtotime($reservation['start_time'])));
//    $_SESSION['canChangeReservation']['amount_people'] = test_input($reservation['amount_people']);
//    $_SESSION['canChangeReservation']['full_name'] = test_input($reservation['full_name']);
//    $_SESSION['canChangeReservation']['emailadres'] = test_input($reservation['emailadres']);
//    $_SESSION['canChangeReservation']['phonenumber'] = test_input($reservation['phonenumber']);
//    $_SESSION['canChangeReservation']['comments'] = test_input($reservation['comments']);
//
//    $_SESSION['canChangeReservation']['all_egg'] = $reservation['all_egg'];
//    $_SESSION['canChangeReservation']['all_gluten'] = $reservation['all_gluten'];
//    $_SESSION['canChangeReservation']['all_lupine'] = $reservation['all_lupine'];
//    $_SESSION['canChangeReservation']['all_milk'] = $reservation['all_milk'];
//    $_SESSION['canChangeReservation']['all_mustard'] = $reservation['all_mustard'];
//    $_SESSION['canChangeReservation']['all_nuts'] = $reservation['all_nuts'];
//    $_SESSION['canChangeReservation']['all_peanut'] = $reservation['all_peanut'];
//    $_SESSION['canChangeReservation']['all_shell'] = $reservation['all_shell'];
//    $_SESSION['canChangeReservation']['all_celery'] = $reservation['all_celery'];
//    $_SESSION['canChangeReservation']['all_sesame'] = $reservation['all_sesame'];
//    $_SESSION['canChangeReservation']['all_soja'] = $reservation['all_soja'];
//    $_SESSION['canChangeReservation']['all_fish'] = $reservation['all_fish'];
//    $_SESSION['canChangeReservation']['all_mollusks'] = $reservation['all_mollusks'];
//    $_SESSION['canChangeReservation']['all_sulfur'] = $reservation['all_sulfur'];
//
//    $date = $_SESSION['canChangeReservation']['date'];
//    $time = $_SESSION['canChangeReservation']['start_time'];
//    $people = $_SESSION['canChangeReservation']['amount_people'];
//    $name = $_SESSION['canChangeReservation']['full_name'];
//    $emailadres = $_SESSION['canChangeReservation']['emailadres'];
//    $phonenumber = $_SESSION['canChangeReservation']['phonenumber'];
//    $comments = $_SESSION['canChangeReservation']['comments'];
//    $allergie_string = $reservation['str_all'];
//
//    $_SESSION['canChangeReservation']['time'] = $time;
//    $_SESSION['canChangeReservation']['people'] = $people;
//    $_SESSION['canChangeReservation']['name'] = $name;
//    header('Location: ../index.php?edit=1');
//    exit;
//}
//
//
//if (isset($_POST['submitDelete'])) {
//    if (isset($_POST['reason']) && $_POST['reason'] !== '') {
//        $reservationIdQuery = mysqli_escape_string($db, $reservation['reservering_id']);
//        $reason = mysqli_escape_string($db, $_POST['reason']);
//        $currentTime = date("Y-m-d H:i:s");
//        $userDelete = "employee";
//        $deleteMail = "false";
//        $deleteQuery = "UPDATE reserveringen SET  date_updated_reservation = '$currentTime', deleted_by_user = '$userDelete', reason_of_deletion = '$reason', delete_mail_sent = '$deleteMail' WHERE reservering_id = '$reservationIdQuery'";
//        $resultDelete = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
//        mysqli_close($db);
//        if ($resultDelete) {
//            header('Location: ./');
//            exit;
//        } else {
//            header('Location: ./details.php?id=' . $reservation['reservering_id'] . '&error=dbError#open');
//            exit;
//        }
//    } else {
//        header('Location: ./details.php?id=' . $reservation['reservering_id'] . '&error=noReason#open');
//        exit;
//    }
//}

print_r($setting);

echo date('N');

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
/**@var string $footer */
require_once "../includes/head.php";
if ($setting['type'] == 'general'){
    oneDotOrMoreHead('..', 'Algemene Daginstellingen bij Rasa Senang');
} else {
    oneDotOrMoreHead('..', 'Daginstelling ' . htmlentities($setting['id']) . ' bij Rasa Senang');
}
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
            <h3><?php if ($setting['type'] == 'general'){
                    echo 'Algemene Daginstellingen';
                } else {
                    echo 'Daginstelling ' . htmlentities($setting['id']);
                } ?></h3>
        </header>

        <div class="details">
            <div class="flexDetails">
                <div class="labelDetails">Accepteer reserveringen:</div>
                <div> <?php if ($setting['accept_reservations'] == 'true') {
                        echo 'Ja';
                    } else {
                        echo 'Nee';
                    } ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Restaurant geopend:</div>
                <div> <?php if ($setting['open_closed'] == 'open') {
                        echo 'Geopend';
                    } else {
                        echo 'Gesloten';
                    } ?></div>
            </div>
            <?php if ($setting['type'] !== 'general') { ?>
                <div class="flexDetails">
                    <div class="labelDetails">Deze regel geldt vanaf:</div>
                    <div><?= date("d/m/Y", strtotime($setting['from_date'])); ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Tot en met:</div>
                    <div><?= date("d/m/Y", strtotime($setting['until_date'])); ?></div>
                </div>
            <?php } ?>
            <div class="flexDetails">
                <div class="labelDetails">Maximaal aantal gasten:</div>
                <div> <?= htmlentities($setting['guest_limit']) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Maximaal aantal reserveringen:</div>
                <div> <?= htmlentities($setting['reservations_limit']) ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Aanvangstijden:</div>
                <div> <?= htmlentities($time_string) ?></div>
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
                <form action="<?php echo ($_SERVER["PHP_SELF"]) . '?id=' . $setting['id']; ?>"
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