<?php
session_start();

if (isset($_SESSION['reservation'])) {
    unset($_SESSION['reservation']);
}

if (isset($_SESSION['canChangeReservation'])) {
    unset($_SESSION['canChangeReservation']);
}

//May I even visit this page?
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: ../inloggen/");
    exit;
}

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

if (isset($_POST['change'])) {
    $_SESSION['canChangeEmployee'] = [
        'employee_id' => mysqli_escape_string($db, $employeeID),
    ];
    header('Location: ../inloggen/register.php?edit=1');
    exit;
}


if (isset($_POST['submitDelete'])) {
    $employeeIdQuery = mysqli_escape_string($db, $employee['id']);
    $deleteQuery = "DELETE FROM users WHERE id = '$employeeIdQuery'";
    $result3 = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
    if ($result) {
        mysqli_close($db);
        header('Location: ./');
        exit;
    } else {
        $errors['general'] = 'Er is helaas iets fout gegaan, probeer het later opnieuw.';
        mysqli_close($db);
    }
}
?>
<!doctype html>
<html lang="nl">
<head>
    <title><?= ' Medewerker ' . htmlentities($employee['username']) ?> bij Rasa Senang</title>
</head>
<body>
<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="./">
        <button class="back">
            <img src="../data/icon-general/back.png" alt="Terug naar Reserveringen">
        </button>
    </a>
</header>

<div class="overlay"></div>
<div class="overlaymodal"></div>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <h1>Details</h1>
            <h3><?= ' Medewerker ' . htmlentities($employee['username']) ?></h3>
        </header>

        <div class="details">
            <div class="flexDetails">
                <div class="labelDetails">Naam:</div>
                <div><?= $employee['name'] ?></div>
            </div>
            <h3 class="h3detailsEmp">Rechten:</h3>
            <div class="flexDetails">
                <div class="labelDetails">Overzicht Reserveringen:</div>
                <div> <?= $employee['can_visit_reservations'] ?></div>
            </div>
            <div class="flexDetails">
                <div class="labelDetails">Overzicht Medewerkers:</div>
                <div> <?= $employee['can_visit_employees'] ?></div>
            </div>
            <?php /*
                <div class="flexDetails">
                    <div class="labelDetails">Daginstellingen:</div>
                    <div> <?= $employee['can_visit_daysettings'] ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Tafelindeling:</div>
                    <div> <?= $employee['can_visit_table'] ?></div>
                </div>
        */ ?>
        </div>

        <div class="detailsPageButtons">
            <div class="flexButtons">
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
                    <p class="errors">Let op: deze actie is permanent!</p>
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
        </div>
    </main>
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</body>
</html>