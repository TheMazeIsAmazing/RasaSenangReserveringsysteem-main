<?php
session_start();

date_default_timezone_set("Europe/Amsterdam");

$date = date("Y-m-d");

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/logincheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');


if (isset($_POST['submit'])) {
    if ($_POST['date'] !== '') {
        $date = date("Y-m-d", strtotime($_POST['date']));
        if (date("Y-m-d", strtotime($date)) < date("Y-m-d", strtotime("2021-12-16"))) {
            $query = "SELECT * FROM `daySettings` ORDER BY `type`, `from_date`";
        } else {
            $query = "SELECT * FROM reserveringen WHERE date = '$date' AND `deleted_by_user` IS NULL AND `reason_of_deletion` IS NULL AND `delete_mail_sent` IS NULL";
        }
    } else {
        $query = "SELECT * FROM `daySettings` ORDER BY `type`, `from_date`";
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
    $query = "SELECT * FROM `daySettings` ORDER BY `type`, `from_date`";
    //Get the result set from the database with a SQL query
    $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
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
    //etc
}

$time_string = "-";


//if (count($allergies) >= 1) {
//    $allergie_string = "Ja: ";
//
//    for ($i = 0; $i < count($allergies); $i++) {
//        if (($i + 1 == count($allergies)) && count($allergies) !== 1) {
//            $allergie_string = "$allergie_string en $allergies[$i]";
//        } elseif (count($allergies) == 1 || $i == 0) {
//            $allergie_string = "$allergie_string $allergies[$i]";
//        } else {
//            $allergie_string = "$allergie_string, $allergies[$i]";
//        }
//    }
//    $allergie_string = htmlentities($allergie_string);
//}

?>
<!doctype html>
<html lang="nl">
<head>
    <title>Daginstellingen van Rasa Senang</title>
</head>
<body>
<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="./">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Beginpagina">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Regels Overzicht</h1>
        </header>
        <div class="search-bar">
            <div class="search-bar-item">
                <button class="date-submit">
                    <a href="../">
                        Nieuwe Regel
                    </a>
                </button>
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
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
