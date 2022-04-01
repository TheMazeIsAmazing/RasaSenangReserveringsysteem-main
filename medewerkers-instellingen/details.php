<?php
session_start();

if (isset($_SESSION['reservation'])) {
    unset($_SESSION['reservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

// redirect when uri does not contain a id
if (!isset($_GET['id']) || $_GET['id'] == '') {
    // redirect to index.php
    header('Location: ./');
    exit;
}

//Retrieve the GET parameter from the 'Super global'
$employeeID = mysqli_escape_string($db, $_GET['id']);

//Get the record from the database result
$query = "SELECT * FROM users WHERE id = '$employeeID'";
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query)

if (mysqli_num_rows($result) !== 1) {
    // redirect when db returns no result
    header('Location: ./');
    exit;
}

$employee = mysqli_fetch_assoc($result);

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
if (htmlentities($employee['username']) !== htmlentities($_SESSION['loggedInUser']['username'])) {
    loginCheckPageSpecific('can_visit_employees');
}

if (isset($_POST['change'])) {
    $_SESSION['canChangeEmployee'] = [
        'employee_id' => mysqli_escape_string($db, $employeeID),
    ];
    if (htmlentities($employee['username']) !== htmlentities($_SESSION['loggedInUser']['username'])) {
        header('Location: ./nieuwe-gebruiker.php?edit=1');
        exit;
    } else {
        header('Location: ./wijzigen-wachtwoord.php');
        exit;
    }
}


if (isset($_POST['submitDelete'])) {
    $employeeIdQuery = mysqli_escape_string($db, $employee['id']);
    $deleteQuery = "DELETE FROM users WHERE id = '$employeeIdQuery'";
    $resultDeletion = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
    mysqli_close($db);
    if ($resultDeletion) {
        header('Location: ./');
        exit;
    } else {
        header('Location: ./details.php?id=' . $employee['id'] . '&error=dbError#open');
        exit;
    }
}

//include basic pages such as navbar and header.
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Medewerker ' . htmlentities($employee['username']) . ' bij Rasa Senang', true);
require_once "../includes/topBar.php";
oneDotOrMoreTopBar('..', './');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');
?>
<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Details</h1>
            <h3><?= 'Medewerker ' . htmlentities($employee['username']) ?></h3>
        </header>

        <div class="details">
            <div class="flexDetails">
                <div class="labelDetails">Naam:</div>
                <div><?= $employee['name'] ?></div>
            </div>
            <?php if (htmlentities($employee['username']) !== htmlentities($_SESSION['loggedInUser']['username'])) { ?>
                <h3 class="h3detailsEmp">Deze gebruiker mag naar de volgende pagina's:</h3>
            <?php } else { ?>
                <h3 class="h3detailsEmp">U mag naar de volgende pagina's:</h3>
            <?php } ?>
            <div class="flexDetails">
                <div class="labelDetails">Overzicht Reserveringen:</div>
                <div> <?php if ($employee['can_visit_reservations'] == 'true') {
                        echo 'Ja';
                    } else {
                        echo 'Nee';
                    } ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Overzicht Medewerkers:</div>
                <div> <?php if ($employee['can_visit_employees'] == 'true') {
                        echo 'Ja';
                    } else {
                        echo 'Nee';
                    } ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Daginstellingen:</div>
                <div> <?php if ($employee['can_visit_daysettings'] == 'true') {
                        echo 'Ja';
                    } else {
                        echo 'Nee';
                    } ?></div>
            </div>
            <?php /*
                <div class="flexDetails">
                    <div class="labelDetails">Tafelindeling:</div>
 <div> <?php if ($employee['can_visit_table'] == 'true') {echo 'Ja';} else { echo 'Nee';} ?></div>
                </div>
        */ ?>
        </div>

        <div class="detailsPageButtons">
            <div class="flexButtons">
                <?php if (htmlentities($employee['username']) !== htmlentities($_SESSION['loggedInUser']['username'])) { ?>
                <form action="" method="post">
                    <input class="date-submit" type="submit" name="change" value="Wijzigen"/>
                </form>
                <button class="date-submit" type="button" data-modal-target="#modal">Verwijderen</button>
            </div>
        </div>
        <div class="modal" id="modal">
            <div class="modal-header">
                <div class="title"> Weet u zeker dat u deze Medewerker wilt verwijderen?</div>
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
                <div class="modalAlignCenter">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $employeeID; ?>"
                          method="post">
                        <div class="date-submit-div">
                            <input class="date-submit" type="submit" name="submitDelete" value="Verwijderen"/>
                        </div>
                    </form>
                </div>
            </div>

            <?php } else {?>
            <form action="" method="post">
                <input class="date-submit" type="submit" name="change" value="Wachtwoord Wijzigen"/>
            </form>
            <?php }?>
        </div>
    </main>
    <?php require_once('../includes/footer.php');
    oneDotOrMoreFooter('..'); ?>
</div>
</body>
</html>