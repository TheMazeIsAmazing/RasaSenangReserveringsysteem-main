<?php
session_start();

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/logincheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_employees');

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');


$query = "SELECT * FROM users";
//Get the result set from the database with a SQL query
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
//Loop through the result to create a custom array
$employees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}
//Close connection
mysqli_close($db);

?>
<!doctype html>
<html lang="nl">
<head>
    <title>Medewekers van Rasa Senang</title>
</head>
<body>
<header>
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="../medewerkers">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Beginpagina">
        </button>
    </a>
</header>

<div class="overlay"></div>

<div class="page-container">
    <main>
        <div class="content-wrap">
            <div>
                <h1>Overzicht Medewerkers</h1>
            </div>
            <div class="search-bar">
                <button class="date-submit">
                    <a href="../inloggen/register.php">
                        Nieuwe Medewerker
                    </a>
                </button>
            </div>
            <section class="align-middle">
                <table class="middle-table">
                    <thead>
                    <tr>
                        <th>Gebruikersnaam</th>
                        <th>Naam</th>
                        <th>Mag naar Overzicht Reserveringen</th>
                        <th>Mag naar Overzicht Medewerkers</th>
                        <th></th>
                        <?php /*
                        //<th>Daginstellingen</th>
                        //<th>Tafelindeling</th> */?>
                        <th colspan="3"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($employees as $employee) {
                        ?>
                        <tr>
                            <td><?= htmlentities($employee['username']) ?></td>
                            <td><?= htmlentities($employee['name']) ?></td>
                            <td><?= htmlentities($employee['can_visit_reservations']) ?></td>
                            <td><?= htmlentities($employee['can_visit_employees']) ?></td>
                            <?php /*
                            <td><?= htmlentities($employee['can_visit_daysettings']) ?></td>
                            <td><?= htmlentities($employee['can_visit_table']) ?></td> */ ?>
                            <td><a href="details.php?id=<?= htmlentities($employee['id']) ?>"><img
                                            class="details-button" src="../data/icon-general/information.png"
                                            alt="Details"></a>
                            </td>
                        </tr>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>