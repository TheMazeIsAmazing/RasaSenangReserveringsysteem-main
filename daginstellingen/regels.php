<?php
session_start();

date_default_timezone_set("Europe/Amsterdam");

$date = date("Y-m-d");

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

if (isset($_POST['submit'])) {
    if ($_POST['date'] !== '') {
        $date = date("Y-m-d", strtotime($_POST['date']));
        if (date("Y-m-d", strtotime($date)) < date("Y-m-d", strtotime("2021-12-16"))) {
            $query = "SELECT * FROM `day-settings` ORDER BY `type`, `from_date`";
        } else {
            $query = "SELECT * FROM `day-settings` WHERE date = '$date' AND `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL";
        }
    } else {
        $query = "SELECT * FROM `day-settings` ORDER BY `type`, `from_date`";
    }
    //Get the result set from the database with a SQL query
    $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
    //Loop through the result to create a custom array
    $settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[] = $row;
    }
    //Close connection
    mysqli_close($db);
} else {
    $query = "SELECT * FROM `day-settings` ORDER BY `type`, `from_date`";
    //Get the result set from the database with a SQL query
    $result = mysqli_query($db, $query) or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
    //Loop through the result to create a custom array
    $settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[] = $row;
    }
    //Close connection
    mysqli_close($db);
}

$times = [];

foreach ($settings as $setting) {
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
    $time_string = htmlentities($time_string);
}

//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Daginstellingen van Rasa Senang');
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', './');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Regels Overzicht</h1>
        </header>
        <section class="search-bar-container">
        <div class="search-bar">
            <div class="search-bar-item">
                <a href="">
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
                    <th>Limiet Gasten</th>
                    <th>Limiet Reserveringen</th>
                    <th>Aanvangstijden</th>
                    <th>Tijdslots</th>
                    <th colspan="3"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($settings as $setting) {
                    if (date("Y m d", strtotime($setting['from_date'])) >= date("Y m d") || $setting['type'] == 'general') { ?>
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
                            <td><?php if ($setting['16:00'] == 'true') {
                                    echo '16:00';
                                } else {
                                    echo '-';
                                } ?></td>
                            <td>
                                <a href="details.php?id=<?= htmlentities($setting['id']) ?>"><img
                                            class="details-button" src="../data/icon-general/information.png"
                                            alt="Details"></a>
                            </td>
                        </tr>
                    <?php }
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
