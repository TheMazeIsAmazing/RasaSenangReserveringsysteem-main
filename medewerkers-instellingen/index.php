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
/**@var string $footer */
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
<header class="topBar">
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
    <main class="content-wrap">
        <header>
            <h1>Overzicht Medewerkers</h1>
        </header>
        <div class="search-bar">
            <button class="date-submit">
                <a href="nieuwe-gebruiker.php">
                    Nieuwe Medewerker
                </a>
            </button>
            <button class="date-submit">
                <a href="details.php?id=<?= htmlentities($_SESSION['loggedInUser']['id']) ?>">Mijn Account</a>
            </button>
        </div>
        <section class="align-middle">
            <table class="middle-table">
                <thead>
                <tr class="rights">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Rechten</th>
                    <th></th>
                    <th></th>
                    <?php //<th>Tafelindeling</th>?>
                    <th colspan="6"></th>
                </tr>
                <tr>
                    <th>Gebruikersnaam</th>
                    <th>Naam</th>
                    <th>Overzicht Reserveringen</th>
                    <th>Medewerkers Instellingen</th>
                    <th>Daginstellingen</th>
                    <th></th>
                    <?php //<th>Tafelindeling</th>?>
                    <th colspan="6"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee) {
                    if (htmlentities($employee['username']) !== htmlentities($_SESSION['loggedInUser']['username'])) {
                        ?>
                        <tr>
                            <td><?= htmlentities($employee['username']) ?></td>
                            <td><?= htmlentities($employee['name']) ?></td>
                            <td><?= htmlentities($employee['can_visit_reservations']) ?></td>
                            <td><?= htmlentities($employee['can_visit_employees']) ?></td>
                            <td><?= htmlentities($employee['can_visit_daysettings']) ?></td>
                            <?php /*

                            <td><?= htmlentities($employee['can_visit_table']) ?></td> */ ?>
                            <td><a href="details.php?id=<?= htmlentities($employee['id']) ?>"><img
                                            class="details-button" src="../data/icon-general/information.png"
                                            alt="Details"></a>
                            </td>
                        </tr>
                        <?php
                    }
                } ?>
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