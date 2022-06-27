<?php
session_start();

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
//loginCheckPageSpecific('can_visit_employees');

$query = "SELECT * FROM `logs` ORDER BY `logs`.`id` DESC";
//Get the result set from the database with a SQL query
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
//Loop through the result to create a custom array
$logs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $logs[] = $row;
}
//Close connection
mysqli_close($db);

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
initializeHead('..', 'Logboeken bij Rasa Senang', false, true, false, false);
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', '../medewerkers');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', true);


?>
    <main class="content-wrap">
        <header>
            <h1>Logboek</h1>
        </header>
        <section class="align-middle">
            <table class="middle-table">
                <thead>
                <tr>
                    <th>Tijd</th>
                    <th>Gebruiker</th>
                    <th>Naam</th>
                    <th>Onderdeel</th>
                    <th>Actie</th>
                    <th>Browser</th>
                    <th>OS</th>
                    <th colspan="6"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logs as $log) { ?>
                    <tr>
                        <td><?= htmlentities($log['date_time']) ?></td>
                        <td><?= htmlentities($log['user_status']) ?></td>
                        <td><?= htmlentities($log['user_name']) ?></td>
                        <td><?= htmlentities($log['class']) ?></td>
                        <td><?= htmlentities($log['action']) ?></td>
                        <td><?= htmlentities($log['browser']) ?></td>
                        <td><?= htmlentities($log['os']) ?></td>
                        <td>
                        <?php if ($log['action'] !== 'delete') {
                            if ($log['class'] == 'reservation') { ?>
                                    <a href="../overzicht-reserveringen/details.php?id=<?= htmlentities($log['id_of_class']) ?>"><img
                                                class="details-button" src="../data/icon-general/information.png"
                                                alt="Details"></a>
                            <?php } else if ($log['class'] == 'employee') { ?>

                                    <a href="../medewerkers-instellingen/details.php?id=<?= htmlentities($log['id_of_class']) ?>"><img
                                                class="details-button" src="../data/icon-general/information.png"
                                                alt="Details"></a>

                            <?php } else if ($log['class'] == 'daysetting') { ?>

                                    <a href="../daginstellingen/details.php?id=<?= htmlentities($log['id_of_class']) ?>"><img
                                                class="details-button" src="../data/icon-general/information.png"
                                                alt="Details"></a>

                            <?php }
                        } ?>
                        </td>
                    </tr>
                    <?php

                } ?>
                </tbody>
            </table>
        </section>
    </main>
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>