<?php
session_start();

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_employees');

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

//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Medewerkers van Rasa Senang', false);
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', '../medewerkers');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Overzicht Medewerkers</h1>
        </header>
        <section class="search-bar-container">
        <div class="search-bar">
            <a href="nieuwe-gebruiker.php">
                <button class="date-submit">
                    Nieuwe Medewerker
                </button>
            </a>
            <a href="details.php?id=<?= htmlentities($_SESSION['loggedInUser']['id']) ?>">
                <button class="date-submit">
                    Mijn Account
                </button>
            </a>
        </div>
        </section>
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
    <?php require_once('../includes/footer.php');
    oneDotOrMoreFooter('..'); ?>
</div>
</body>
</html>