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

while ($row = mysqli_fetch_assoc($resultDaySettings)) {
    $settings[] = $row;
}

foreach ($settings as $key => $setting) {
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

    if (count($times) >= 1) {
        $settings[$key]['time-str'] = '';
        for ($i = 0; $i < count($times); $i++) {
            if (($i + 1 == count($times)) && count($times) !== 1) {
                $settings[$key]['time-str'] = "{$settings[$key]['time-str']} en $times[$i]";
            } elseif (count($times) == 1 || $i == 0) {
                $settings[$key]['time-str'] = "{$settings[$key]['time-str']} $times[$i]";
            } else {
                $settings[$key]['time-str'] = "{$settings[$key]['time-str']}, $times[$i]";
            }
        }
    }

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
    $settings[$key]['date-str'] = '';
    for ($i = 0; $i < count($days); $i++) {
        if (($i + 1 == count($days)) && count($days) !== 1) {
            $settings[$key]['date-str'] = "{$settings[$key]['date-str']} en $days[$i]";
        } elseif (count($days) == 1 || $i == 0) {
            $settings[$key]['date-str'] = "{$settings[$key]['date-str']} $days[$i]";
        } else {
            $settings[$key]['date-str'] = "{$settings[$key]['date-str']}, $days[$i]";
        }
    }
}
}

$daysMatchQuery = 0;

//print_r($settings);

$date = date('Y-m-d');

$queryReservations = "SELECT * FROM reserveringen WHERE date = '$date' AND `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL";
$resultReservations = mysqli_query($db, $queryReservations); //or die('Error: ' . mysqli_error($db) . ' with query ' . $queryReservations);
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



//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Daginstellingen van Rasa Senang', false, false);
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', '../medewerkers');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..', false);
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Daginstellingen</h1>
        </header>
        <section class="search-bar-container">
            <div class="search-bar">
                <a href="../daginstellingen/regels.php">
                    <button class="date-submit">
                        Regels overzicht
                    </button>
                </a>
            </div>
        </section>
        <div class="flexDaySettings">
            <div class="daySummary">
                <h2>Instellingen voor Vandaag:</h2>
                <?php foreach ($settings as $setting) {
                if ((strtotime($setting['until_date']) >= strtotime($date)) && (strtotime($setting['from_date']) <= strtotime($date))) {
                    $daysMatchQuery++;
                if ($setting[strtolower(date('l'))] == 'open') {;
                ?>
                <div class="flexDetails">
                    <div class="labelDetails">Accepteer Reserveringen:</div>
                    <div><?php if (htmlentities($setting['accept_reservations']) == 'true') {
                            echo 'Ja';
                        } else {
                            echo 'Nee';
                        } ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Restaurant open/dicht:</div>
                    <div><?php if (htmlentities($setting['open_closed']) == 'open') {
                            echo 'Geopend';
                        } else {
                            echo 'Gesloten';
                        } ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Gasten limiet:</div>
                    <div class="daySummaryFlexListItem"><?= htmlentities($setting['guest_limit']) ?> <div>(Er komen vandaag <?= $guestCount; ?> gasten)</div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Reservering limiet:</div>
                    <div class="daySummaryFlexListItem"><?= htmlentities($setting['reservations_limit']) ?> <div>(Er zijn vandaag <?= $amountReservations; ?> reserveringen)</div></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails timesLabel">Beschikbare tijden:</div>
                    <div><?= $setting['time-str']; ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Beschikbare tijdsloten:</div>
                    <div></div>
                </div>
            </div>
            <?php } else { ?>
                <div class="flexDetails">
                    <div class="labelDetails">Restaurant open/dicht:</div>
                    <div>Gesloten</div>
                </div>
        </div>
            <?php }  } }
            if ($daysMatchQuery == 0) {
                foreach ($settings as $setting) {
                    if ($setting['type'] == 'general') {
                        if ($setting[strtolower(date('l'))] == 'open' && $setting['open_closed'] == 'open') {?>
            <div class="flexDetails">
                <div class="labelDetails">Accepteer Reserveringen:</div>
                <div><?php if (htmlentities($setting['accept_reservations']) == 'true') {
                        echo 'Ja';
                    } else {
                        echo 'Nee';
                    } ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Restaurant open/dicht:</div>
                <div>Geopend</div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Gasten limiet:</div>
                <div><?= htmlentities($setting['guest_limit']) ?> (Er komen vandaag <?= $guestCount; ?> gasten)</div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Reservering limiet:</div>
                <div><?= htmlentities($setting['reservations_limit']) ?> (Er zijn vandaag <?= $amountReservations; ?> reserveringen)
                </div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails timesLabel">Beschikbare tijden:</div>
                <div><?= $setting['time-str']; ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Beschikbare tijdsloten:</div>
                <div></div>
            </div>
                                        </div>
            <?php } else { ?>
                            <div class="flexDetails">
                                <div class="labelDetails">Restaurant open/dicht:</div>
                                <div>Gesloten</div>
                            </div>
                                        </div>
            <?php        }}}} ?>
            <div class="daySummary">
                <h2>Algemene Instellingen:</h2>
                <?php foreach ($settings as $setting) {
                if ($setting['type'] == 'general') {
                    if (htmlentities($setting['open_closed']) == 'open') { ?>
                <div class="flexDetails">
                    <div class="labelDetails">Accepteer Reserveringen:</div>
                    <div><?php if (htmlentities($setting['accept_reservations']) == 'true') {
                            echo 'Ja';
                        } else {
                            echo 'Nee';
                        } ?></div>
                </div>
                    <?php } ?>
                <div class="flexDetails">
                    <div class="labelDetails">Restaurant open/dicht:</div>
                    <div><?php if (htmlentities($setting['open_closed']) == 'open') {
                            echo 'Geopend';
                        } else {
                            echo 'Gesloten';
                        } ?></div>
                </div>
                    <?php if (htmlentities($setting['open_closed']) == 'open') { ?>
                        <div class="flexDetails">
                            <div class="labelDetails">Gasten limiet:</div>
                            <div><?= htmlentities($setting['guest_limit']) ?></div>
                        </div>
                        <div class="flexDetails">
                            <div class="labelDetails">Reservering limiet:</div>
                            <div><?= htmlentities($setting['reservations_limit']) ?></div>
                        </div>
                        <div class="flexDetails">
                            <div class="labelDetails timesLabel">Beschikbare tijden:</div>
                            <div><?= $setting['time-str']; ?></div>
                        </div>
                        <div class="flexDetails">
                            <div class="labelDetails timesLabel">Geopende dagen:</div>
                            <div><?= $setting['date-str']; ?></div>
                        </div>
                        <div class="flexDetails">
                            <div class="labelDetails">Beschikbare tijdsloten:</div>
                            <div></div>
                        </div>
                    <?php } ?>
                <?php }} ?>
            </div>
        </div>
    </main>
    <?php require_once('../includes/footer.php');
    oneDotOrMoreFooter('..'); ?>
</div>
</body>
</html>