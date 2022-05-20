<?php
session_start();

if (isset($_SESSION['reservation'])) {
    unset($_SESSION['reservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

if (isset($_SESSION['daySettingChange'])) {
    unset($_SESSION['daySettingChange']);
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
    header('Location: ./regels.php');
    exit;
}

$setting = mysqli_fetch_assoc($result);


//construct time string

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

//construct days string

$days = [];

if ($setting['monday'] == 'open') {
    $days[] = 'Maandag';
}
if ($setting['tuesday'] == 'open') {
    $days[] = 'Dinsdag';
}
if ($setting['wednesday'] == 'open') {
    $days[] = 'Woensdag';
}
if ($setting['thursday'] == 'open') {
    $days[] = 'Donderdag';
}
if ($setting['friday'] == 'open') {
    $days[] = 'Vrijdag';
}
if ($setting['saturday'] == 'open') {
    $days[] = 'Zaterdag';
}
if ($setting['sunday'] == 'open') {
    $days[] = 'Zondag';
}

$date_string = "Niet van toepassing";


if (count($days) >= 1) {
    $date_string = "";

    for ($i = 0; $i < count($days); $i++) {
        if (($i + 1 == count($days)) && count($days) !== 1) {
            $date_string = "$date_string en $days[$i]";
        } elseif (count($days) == 1 || $i == 0) {
            $date_string = "$date_string $days[$i]";
        } else {
            $date_string = "$date_string, $days[$i]";
        }
    }
}

if (isset($_POST['change'])) {
    $_SESSION['daySettingChange'] = [
        'setting_id' => mysqli_escape_string($db, $settingID),
    ];
    header('Location: ./new-rule.php?edit=1');
    exit;
}


if (isset($_POST['submitDelete'])) {
    $settingIdQuery = mysqli_escape_string($db, $setting['id']);
    $deleteQuery = "DELETE FROM `day-settings` WHERE id = '$settingIdQuery'";
    $resultDeletion = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
    mysqli_close($db);
    if ($resultDeletion) {
        header('Location: ./regels.php');
        exit;
    } else {
        header('Location: ./details.php?id=' . $setting['id'] . '&error=dbError#open');
        exit;
    }
}


//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
if ($setting['type'] == 'general') {
    initializeHead('..', 'Algemene Daginstellingen bij Rasa Senang', true, false, false, false);
} else {
    initializeHead('..', 'Daginstelling ' . htmlentities($setting['id']) . ' bij Rasa Senang', true, false, false, false);
}
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', './regels.php');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
    <main class="content-wrap">
        <header>
            <h1>Details</h1>
            <h3><?php if ($setting['type'] == 'general') {
                    echo 'Algemene Daginstellingen';
                } else {
                    echo 'Daginstelling ' . htmlentities($setting['id']);
                } ?></h3>
        </header>

        <div class="details">
            <?php if ($setting['open_closed'] == 'open') { ?>
                <div class="flexDetails">
                    <div class="labelDetails">Restaurant geopend:</div>
                    <div>Geopend</div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Accepteer reserveringen:</div>
                    <div> <?php if ($setting['accept_reservations'] == 'true') {
                            echo 'Ja';
                        } else {
                            echo 'Nee';
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
<!--                --><?php //if ($setting['times_or_timeslots'] == 'times') { ?>
<!--                    <div class="flexDetails">-->
<!--                        <div class="labelDetails timesLabel">Beschikbare tijden:</div>-->
<!--                        <div>--><?//= htmlentities($time_string) ?><!--</div>-->
<!--                    </div>-->
<!--                --><?php //} else { ?>
<!--                    <div class="flexDetails">-->
<!--                        <div class="labelDetails timesLabel">Tijdsloten:</div>-->
<!--                        <div>--><?//= 'Slot 1 vanaf: ' . $setting['timeslot_1_from'] . ' tot ' . $setting['timeslot_1_to'] . '; Slot 2 vanaf: ' . $setting['timeslot_2_from'] . ' tot ' . $setting['timeslot_2_to']; ?><!--</div>-->
<!--                    </div>-->
<!--                --><?php //} ?>
                <div class="flexDetails">
                    <div class="labelDetails">Dagen Geopend:</div>
                    <div> <?= htmlentities($date_string) ?></div>
                </div>
            <?php } else { ?>
            <div class="flexDetails">
                <div class="labelDetails">Restaurant geopend:</div>
                <div> Gesloten </div>
            </div>
        <?php } ?>
        </div>
        <div class="detailsPageButtons">
            <div class="flexButtons">
                <form action="" method="post">
                    <input class="date-submit" type="submit" name="change" value="Wijzigen"/>
                </form>
                <?php if ($setting['type'] !== 'general') { ?>
                <button class="date-submit" type="button" data-modal-target="#modal">Verwijderen</button>
            </div>
        </div>
        <div class="modal" id="modal"
             <?php if (isset($_GET['error']) && $_GET['error'] !== '') { ?>style="transition: none" <?php } ?>>
            <div class="modal-header">
                <div class="title"> Weet u zeker dat u deze instelling wilt verwijderen?</div>
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
                            } else {
                                echo "Let op: deze actie is permanent!";
                            }
                        } else {
                            echo "Let op: deze actie is permanent!";
                        } ?></p>
                </div>
                <div class="modalAlignCenter">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $settingID; ?>"
                          method="post">
                        <div class="date-submit-div">
                            <input class="date-submit" type="submit" name="submitDelete" value="Verwijderen"/>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>