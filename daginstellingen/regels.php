<?php
session_start();

date_default_timezone_set("Europe/Amsterdam");

if (isset($_POST['date'])) {
    $date = date("Y-m-d", strtotime($_POST['date']));
} else {
    $date = date("Y-m-d");
}

if ($date < date("Y-m-d", strtotime('12 december 2021'))) {
    $date = date("Y-m-d");
}

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

$query = "SELECT * FROM `day-settings` ORDER BY `type`, `from_date`";
//Get the result set from the database with a SQL query
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
//Loop through the result to create a custom array
$settings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $settings[] = $row;
}
//Close connection
mysqli_close($db);


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
}

print_r($settings);

//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Daginstellingen van Rasa Senang', false, true);
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', './');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..', true);
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Regels Overzicht</h1>
        </header>
        <section class="search-bar-container">
            <div class="search-bar">
                <div class="search-bar-item">
                    <a href="./new-rule.php">
                        <button class="date-submit">
                            Nieuwe Regel
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
            <table class="middle-table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Vanaf</th>
                    <th>Tot en Met</th>
                    <th>Accepteer Reserveringen</th>
                    <th>Open?</th>
                    <th>Limiet Gasten</th>
                    <th>Limiet Reserveringen</th>
                    <th>Aanvangstijden of Tijdslots</th>
                    <th colspan="3"></th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($_POST['submit'])) {
                    foreach ($settings as $setting) {
                        if (((strtotime($setting['until_date']) >= strtotime($date)) && (strtotime($setting['from_date']) <= strtotime($date))) || $setting['type'] == 'general') {?>
                            <tr>
                                <td><?php if (htmlentities($setting['type']) == 'general') {
                                        echo 'Algemeen';
                                    } else {
                                        echo 'Specifieke Regel';
                                    } ?></td>
                                <td><?php if (isset($setting['from_date']) && $setting['from_date'] !== '') {
                                        echo $setting['from_date'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?php if (isset($setting['until_date']) && $setting['until_date'] !== '') {
                                        echo $setting['until_date'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?php if (htmlentities($setting['accept_reservations']) == 'true') {
                                        echo 'Ja';
                                    } else {
                                        echo 'Nee';
                                    } ?></td>
                                <td><?php if (htmlentities($setting['open_closed']) == 'open') {
                                        echo 'Geopend';
                                    } else {
                                        echo 'Gesloten';
                                    } ?></td>
                                <td><?php if (isset($setting['guest_limit']) && $setting['guest_limit'] !== '') {
                                        echo $setting['guest_limit'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?php if (isset($setting['reservations_limit']) && $setting['reservations_limit'] !== '') {
                                        echo $setting['reservations_limit'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?= $setting['time-str']; ?></td>
                                <td>
                                    <a href="details.php?id=<?= htmlentities($setting['id']) ?>"><img
                                                class="details-button" src="../data/icon-general/information.png"
                                                alt="Details"></a>
                                </td>
                            </tr>
                        <?php }
                    }
                } else {
                    foreach ($settings as $setting) {
                        if (date("Y m d", strtotime($setting['until_date'])) >= date("Y m d") || $setting['type'] == 'general') { ?>
                            <tr>
                                <td><?php if (htmlentities($setting['type']) == 'general') {
                                        echo 'Algemeen';
                                    } else {
                                        echo 'Specifieke Regel';
                                    } ?></td>
                                <td><?php if (isset($setting['from_date']) && $setting['from_date'] !== '') {
                                        echo $setting['from_date'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?php if (isset($setting['until_date']) && $setting['until_date'] !== '') {
                                        echo $setting['until_date'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?php if (htmlentities($setting['accept_reservations']) == 'true') {
                                        echo 'Ja';
                                    } else {
                                        echo 'Nee';
                                    } ?></td>
                                <td><?php if (htmlentities($setting['open_closed']) == 'open') {
                                        echo 'Geopend';
                                    } else {
                                        echo 'Gesloten';
                                    } ?></td>
                                <td><?php if (isset($setting['guest_limit']) && $setting['guest_limit'] !== '') {
                                        echo $setting['guest_limit'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?php if (isset($setting['reservations_limit']) && $setting['reservations_limit'] !== '') {
                                        echo $setting['reservations_limit'];
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td><?= $setting['time-str']; ?></td>
                                <td>
                                    <a href="details.php?id=<?= htmlentities($setting['id']) ?>"><img
                                                class="details-button" src="../data/icon-general/information.png"
                                                alt="Details"></a>
                                </td>
                            </tr>
                        <?php }
                    }
                }
                ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php require_once('../includes/footer.php');
    oneDotOrMoreFooter('..'); ?>
</div>
</body>
</html>
