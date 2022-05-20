<?php
session_start();

//May I even visit this page?
if (!isset($_SESSION['canChangeReservation']) && !isset($_SESSION['reservation'])) {
    header("Location: ../");
    exit;
}

if ((isset($_GET['edit']) && $_GET['edit'] !== '1') || (isset($_GET['edit']) && !isset($_SESSION['canChangeReservation']))) {
    header('location: ../');
    exit;
}

//Require database in this file
require_once '../includes/database.php';
/** @var mysqli $db */

if (isset($_POST['submitDelete'])) {
    $reservationIdQueryDelete = mysqli_escape_string($db, $_SESSION['canChangeReservation']['reservering_id']);
    $_SESSION['canChangeReservation']['deletion'] = "true";
    $currentTime = date("Y-m-d H:i:s");

    $queryBeforeDeletion = "SELECT * FROM reserveringen WHERE reservering_id = '$reservationIdQueryDelete'";
    $resultBeforeDeletion = mysqli_query($db, $queryBeforeDeletion); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

    $reservation = mysqli_fetch_assoc($resultBeforeDeletion);

    $_SESSION['reservation']['date'] = $reservation['date'];
    $_SESSION['reservation']['time'] = $reservation['start_time'];
    $_SESSION['reservation']['people'] = $reservation['amount_people'];
    $_SESSION['reservation']['name'] = $reservation['full_name'];
    $_SESSION['reservation']['emailadres'] = $reservation['emailadres'];
    $_SESSION['reservation']['phonenumber'] = $reservation['phonenumber'];
    $_SESSION['reservation']['comments'] = $reservation['comments'];
    $_SESSION['reservation']['str_all'] = $reservation['str_all'];


    $deleteQuery = "DELETE FROM reserveringen WHERE reservering_id = '$reservationIdQueryDelete'";
    $resultDelete = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
    mysqli_close($db);
    if ($resultDelete) {
        $_SESSION['deletedReservation'] = "true";
        header('Location: ../bevestiging-reservatie');
        exit;
    } else {
        header('Location: ./index.php?edit=1&error=dbError#open');
        exit;
    }
}

if (isset($_SESSION['canChangeReservation'])) {
    if ($_SESSION['canChangeReservation']['load_check_page'] == "false" && isset($_GET)) {
        $reservering_id = mysqli_escape_string($db, $_SESSION['canChangeReservation']['reservering_id']);
        $query = "SELECT * FROM reserveringen WHERE reservering_id = '$reservering_id'";
        $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

        $reservation = mysqli_fetch_assoc($result);

        $_SESSION['canChangeReservation']['date'] = mysqli_escape_string($db, test_input($reservation['date']));
        $_SESSION['canChangeReservation']['start_time'] = mysqli_escape_string($db, test_input(date("H:i", strtotime($reservation['start_time']))));
        $_SESSION['canChangeReservation']['amount_people'] = mysqli_escape_string($db, test_input($reservation['amount_people']));
        $_SESSION['canChangeReservation']['full_name'] = mysqli_escape_string($db, test_input($reservation['full_name']));
        $_SESSION['canChangeReservation']['emailadres'] = mysqli_escape_string($db, test_input($reservation['emailadres']));
        $_SESSION['canChangeReservation']['phonenumber'] = mysqli_escape_string($db, test_input($reservation['phonenumber']));
        $_SESSION['canChangeReservation']['comments'] = mysqli_escape_string($db, test_input($reservation['comments']));

        $_SESSION['canChangeReservation']['all_egg'] = mysqli_escape_string($db, $reservation['all_egg']);
        $_SESSION['canChangeReservation']['all_gluten'] = mysqli_escape_string($db, $reservation['all_gluten']);
        $_SESSION['canChangeReservation']['all_lupine'] = mysqli_escape_string($db, $reservation['all_lupine']);
        $_SESSION['canChangeReservation']['all_milk'] = mysqli_escape_string($db, $reservation['all_milk']);
        $_SESSION['canChangeReservation']['all_mustard'] = mysqli_escape_string($db, $reservation['all_mustard']);
        $_SESSION['canChangeReservation']['all_nuts'] = mysqli_escape_string($db, $reservation['all_nuts']);
        $_SESSION['canChangeReservation']['all_peanut'] = mysqli_escape_string($db, $reservation['all_peanut']);
        $_SESSION['canChangeReservation']['all_shell'] = mysqli_escape_string($db, $reservation['all_shell']);
        $_SESSION['canChangeReservation']['all_celery'] = mysqli_escape_string($db, $reservation['all_celery']);
        $_SESSION['canChangeReservation']['all_sesame'] = mysqli_escape_string($db, $reservation['all_sesame']);
        $_SESSION['canChangeReservation']['all_soja'] = mysqli_escape_string($db, $reservation['all_soja']);
        $_SESSION['canChangeReservation']['all_fish'] = mysqli_escape_string($db, $reservation['all_fish']);
        $_SESSION['canChangeReservation']['all_mollusks'] = mysqli_escape_string($db, $reservation['all_mollusks']);
        $_SESSION['canChangeReservation']['all_sulfur'] = mysqli_escape_string($db, $reservation['all_sulfur']);

        $date = mysqli_escape_string($db, $_SESSION['canChangeReservation']['date']);
        $time = mysqli_escape_string($db, $_SESSION['canChangeReservation']['start_time']);
        $people = mysqli_escape_string($db, $_SESSION['canChangeReservation']['amount_people']);
        $name = mysqli_escape_string($db, $_SESSION['canChangeReservation']['full_name']);
        $emailadres = mysqli_escape_string($db, $_SESSION['canChangeReservation']['emailadres']);
        $phonenumber = mysqli_escape_string($db, $_SESSION['canChangeReservation']['phonenumber']);
        $comments = mysqli_escape_string($db, $_SESSION['canChangeReservation']['comments']);
        $allergie_string = mysqli_escape_string($db, $reservation['str_all']);

        $_SESSION['canChangeReservation']['time'] = mysqli_escape_string($db, $time);
        $_SESSION['canChangeReservation']['people'] = mysqli_escape_string($db, $people);
        $_SESSION['canChangeReservation']['name'] = mysqli_escape_string($db, $name);


    } else {
        $date = mysqli_escape_string($db, $_SESSION['reservation']['date']);
        $time = mysqli_escape_string($db, $_SESSION['reservation']['time']);
        $people = mysqli_escape_string($db, $_SESSION['reservation']['people']);
        $name = mysqli_escape_string($db, $_SESSION['reservation']['name']);
        $emailadres = mysqli_escape_string($db, $_SESSION['reservation']['emailadres']);
        $phonenumber = mysqli_escape_string($db, $_SESSION['reservation']['phonenumber']);
        $comments = mysqli_escape_string($db, $_SESSION['reservation']['comments']);

//initiate allergies array if there are any:
        $allergies = [];

        if (isset($_SESSION['reservation']['all_egg'])) {
            $allergies[] = "Eieren";
            $allergie_egg = mysqli_escape_string($db, $_SESSION['reservation']['all_egg']);
        } else {
            $allergie_egg = "off";
        }
        if (isset($_SESSION['reservation']['all_gluten'])) {
            $allergies[] = "Gluten";
            $allergie_gluten = mysqli_escape_string($db, $_SESSION['reservation']['all_gluten']);
        } else {
            $allergie_gluten = "off";
        }
        if (isset($_SESSION['reservation']['all_lupine'])) {
            $allergies[] = "Lupine";
            $allergie_lupine = mysqli_escape_string($db, $_SESSION['reservation']['all_lupine']);
        } else {
            $allergie_lupine = "off";
        }
        if (isset($_SESSION['reservation']['all_milk'])) {
            $allergies[] = "Melk";
            $allergie_milk = mysqli_escape_string($db, $_SESSION['reservation']['all_milk']);
        } else {
            $allergie_milk = "off";
        }
        if (isset($_SESSION['reservation']['all_mustard'])) {
            $allergies[] = "Mosterd";
            $allergie_mustard = mysqli_escape_string($db, $_SESSION['reservation']['all_mustard']);
        } else {
            $allergie_mustard = "off";
        }
        if (isset($_SESSION['reservation']['all_nuts'])) {
            $allergies[] = "Noten";
            $allergie_nuts = mysqli_escape_string($db, $_SESSION['reservation']['all_nuts']);
        } else {
            $allergie_nuts = "off";
        }
        if (isset($_SESSION['reservation']['all_peanut'])) {
            $allergies[] = "Pinda";
            $allergie_peanut = mysqli_escape_string($db, $_SESSION['reservation']['all_peanut']);
        } else {
            $allergie_peanut = "off";
        }
        if (isset($_SESSION['reservation']['all_shell'])) {
            $allergies[] = "Schaaldieren";
            $allergie_shell = mysqli_escape_string($db, $_SESSION['reservation']['all_shell']);
        } else {
            $allergie_shell = "off";
        }
        if (isset($_SESSION['reservation']['all_celery'])) {
            $allergies[] = "Selderij";
            $allergie_celery = mysqli_escape_string($db, $_SESSION['reservation']['all_celery']);
        } else {
            $allergie_celery = "off";
        }
        if (isset($_SESSION['reservation']['all_sesame'])) {
            $allergies[] = "Sesamzaad";
            $allergie_sesame = mysqli_escape_string($db, $_SESSION['reservation']['all_sesame']);
        } else {
            $allergie_sesame = "off";
        }
        if (isset($_SESSION['reservation']['all_soja'])) {
            $allergies[] = "Soja";
            $allergie_soja = mysqli_escape_string($db, $_SESSION['reservation']['all_soja']);
        } else {
            $allergie_soja = "off";
        }
        if (isset($_SESSION['reservation']['all_fish'])) {
            $allergies[] = "Vis";
            $allergie_fish = mysqli_escape_string($db, $_SESSION['reservation']['all_fish']);
        } else {
            $allergie_fish = "off";
        }
        if (isset($_SESSION['reservation']['all_mollusks'])) {
            $allergies[] = "Weekdieren";
            $allergie_mollusks = mysqli_escape_string($db, $_SESSION['reservation']['all_mollusks']);
        } else {
            $allergie_mollusks = "off";
        }
        if (isset($_SESSION['reservation']['all_sulfur'])) {
            $allergies[] = "Zwaveldioxide";
            $allergie_sulfur = mysqli_escape_string($db, $_SESSION['reservation']['all_sulfur']);
        } else {
            $allergie_sulfur = "off";
        }

        $allergie_string = "Niet van toepassing.";


        if (count($allergies) >= 1) {
            $allergie_string = "Ja: ";

            for ($i = 0; $i < count($allergies); $i++) {
                if (($i + 1 == count($allergies)) && count($allergies) !== 1) {
                    $allergie_string = "$allergie_string en $allergies[$i]";
                } elseif (count($allergies) == 1 || $i == 0) {
                    $allergie_string = "$allergie_string $allergies[$i]";
                } else {
                    $allergie_string = "$allergie_string, $allergies[$i]";
                }
            }
            $allergie_string = htmlentities($allergie_string);
        }
    }
} else {
    $date = mysqli_escape_string($db, $_SESSION['reservation']['date']);
    $time = mysqli_escape_string($db, $_SESSION['reservation']['time']);
    $people = mysqli_escape_string($db, $_SESSION['reservation']['people']);
    $name = mysqli_escape_string($db, $_SESSION['reservation']['name']);
    $emailadres = mysqli_escape_string($db, $_SESSION['reservation']['emailadres']);
    $phonenumber = mysqli_escape_string($db, $_SESSION['reservation']['phonenumber']);
    $comments = mysqli_escape_string($db, $_SESSION['reservation']['comments']);

//initiate allergies array if there are any:
    $allergies = [];

    if (isset($_SESSION['reservation']['all_egg'])) {
        $allergies[] = "Eieren";
        $allergie_egg = mysqli_escape_string($db, $_SESSION['reservation']['all_egg']);
    } else {
        $allergie_egg = "off";
    }
    if (isset($_SESSION['reservation']['all_gluten'])) {
        $allergies[] = "Gluten";
        $allergie_gluten = mysqli_escape_string($db, $_SESSION['reservation']['all_gluten']);
    } else {
        $allergie_gluten = "off";
    }
    if (isset($_SESSION['reservation']['all_lupine'])) {
        $allergies[] = "Lupine";
        $allergie_lupine = mysqli_escape_string($db, $_SESSION['reservation']['all_lupine']);
    } else {
        $allergie_lupine = "off";
    }
    if (isset($_SESSION['reservation']['all_milk'])) {
        $allergies[] = "Melk";
        $allergie_milk = mysqli_escape_string($db, $_SESSION['reservation']['all_milk']);
    } else {
        $allergie_milk = "off";
    }
    if (isset($_SESSION['reservation']['all_mustard'])) {
        $allergies[] = "Mosterd";
        $allergie_mustard = mysqli_escape_string($db, $_SESSION['reservation']['all_mustard']);
    } else {
        $allergie_mustard = "off";
    }
    if (isset($_SESSION['reservation']['all_nuts'])) {
        $allergies[] = "Noten";
        $allergie_nuts = mysqli_escape_string($db, $_SESSION['reservation']['all_nuts']);
    } else {
        $allergie_nuts = "off";
    }
    if (isset($_SESSION['reservation']['all_peanut'])) {
        $allergies[] = "Pinda";
        $allergie_peanut = mysqli_escape_string($db, $_SESSION['reservation']['all_peanut']);
    } else {
        $allergie_peanut = "off";
    }
    if (isset($_SESSION['reservation']['all_shell'])) {
        $allergies[] = "Schaaldieren";
        $allergie_shell = mysqli_escape_string($db, $_SESSION['reservation']['all_shell']);
    } else {
        $allergie_shell = "off";
    }
    if (isset($_SESSION['reservation']['all_celery'])) {
        $allergies[] = "Selderij";
        $allergie_celery = mysqli_escape_string($db, $_SESSION['reservation']['all_celery']);
    } else {
        $allergie_celery = "off";
    }
    if (isset($_SESSION['reservation']['all_sesame'])) {
        $allergies[] = "Sesamzaad";
        $allergie_sesame = mysqli_escape_string($db, $_SESSION['reservation']['all_sesame']);
    } else {
        $allergie_sesame = "off";
    }
    if (isset($_SESSION['reservation']['all_soja'])) {
        $allergies[] = "Soja";
        $allergie_soja = mysqli_escape_string($db, $_SESSION['reservation']['all_soja']);
    } else {
        $allergie_soja = "off";
    }
    if (isset($_SESSION['reservation']['all_fish'])) {
        $allergies[] = "Vis";
        $allergie_fish = mysqli_escape_string($db, $_SESSION['reservation']['all_fish']);
    } else {
        $allergie_fish = "off";
    }
    if (isset($_SESSION['reservation']['all_mollusks'])) {
        $allergies[] = "Weekdieren";
        $allergie_mollusks = mysqli_escape_string($db, $_SESSION['reservation']['all_mollusks']);
    } else {
        $allergie_mollusks = "off";
    }
    if (isset($_SESSION['reservation']['all_sulfur'])) {
        $allergies[] = "Zwaveldioxide";
        $allergie_sulfur = mysqli_escape_string($db, $_SESSION['reservation']['all_sulfur']);
    } else {
        $allergie_sulfur = "off";
    }

    $allergie_string = "Niet van toepassing.";


    if (count($allergies) >= 1) {
        $allergie_string = "Ja: ";

        for ($i = 0; $i < count($allergies); $i++) {
            if (($i + 1 == count($allergies)) && count($allergies) !== 1) {
                $allergie_string = "$allergie_string en $allergies[$i]";
            } elseif (count($allergies) == 1 || $i == 0) {
                $allergie_string = "$allergie_string $allergies[$i]";
            } else {
                $allergie_string = "$allergie_string, $allergies[$i]";
            }
        }
        $allergie_string = htmlentities($allergie_string);
    }
}

if (isset($_POST['submit'])) {

    date_default_timezone_set("Europe/Amsterdam");

    $currentTime = date("Y-m-d H:i:s");

    if (!isset($_SESSION['canChangeReservation'])) {
        $randomNumber = rand(1000, 9999);
        $_SESSION['reservation']['random-number'] = $randomNumber;
        $_SESSION['reservation']['str_all'] = $allergie_string;
        $queryNewReservation = "INSERT INTO `reserveringen`(date, start_time, amount_people, full_name, emailadres, phonenumber, date_placed_reservation, comments, unique_code, date_updated_reservation, all_egg, all_gluten, all_lupine, all_milk, all_mustard, all_nuts, all_peanut, all_shell, all_celery, all_sesame, all_soja, all_fish, all_mollusks, all_sulfur, str_all) VALUES ('$date', '$time', '$people', '$name', '$emailadres', '$phonenumber', '$currentTime', '$comments', '$randomNumber', '$currentTime', '$allergie_egg', '$allergie_gluten', '$allergie_lupine', '$allergie_milk', '$allergie_mustard', '$allergie_nuts', '$allergie_peanut', '$allergie_shell', '$allergie_celery', '$allergie_sesame', '$allergie_soja', '$allergie_fish', '$allergie_mollusks', '$allergie_sulfur', '$allergie_string')";
        $resultNewReservation = mysqli_query($db, $queryNewReservation) or die('Error: ' . mysqli_error($db) . ' with query ' . $queryNewReservation);
        if ($resultNewReservation) {
            $time = date('H:i:s', strtotime($time));
            $date = date('Y-m-d', strtotime($date));
            $queryNewReservationPullId = "SELECT * FROM `reserveringen` WHERE `unique_code` = '$randomNumber' AND `start_time` = '$time' AND `date` = '$date' AND `emailadres` = '$emailadres' AND `phonenumber` = '$phonenumber'";

            $resultReservationPullId = mysqli_query($db, $queryNewReservationPullId); //or die('Error: ' . mysqli_error($db) . ' with query ' . $queryNewReservationPullId);
            if ($resultReservationPullId) {
                $reservationPullId = mysqli_fetch_assoc($resultReservationPullId);
                $_SESSION['reservation']['reservering_id'] = $reservationPullId['reservering_id'];
                header('Location: ../bevestiging-reservatie');
                exit;
            }
        } else {
            $errors['general'] = 'Er is helaas iets fout gegaan, probeer het later opnieuw.';
        }
    } else {
        $reservering_id = $_SESSION['canChangeReservation']['reservering_id'];

        $queryBeforeChange = "SELECT * FROM reserveringen WHERE reservering_id = '$reservering_id'";
        $resultBeforeChange = mysqli_query($db, $queryBeforeChange); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

        $reservationBeforeChange = mysqli_fetch_assoc($resultBeforeChange);

        $_SESSION['reservation']['random-number'] = $reservationBeforeChange['unique_code'];
        $_SESSION['reservation']['str_all'] = $allergie_string;

        $queryUpdate = "UPDATE `reserveringen` SET date = '$date', start_time = '$time', amount_people = '$people', full_name = '$name', emailadres = '$emailadres', phonenumber = '$phonenumber', comments = '$comments', date_updated_reservation = '$currentTime', all_egg = '$allergie_egg', all_gluten = '$allergie_gluten', all_lupine = '$allergie_lupine', all_milk = '$allergie_milk', all_mustard = '$allergie_mustard', all_nuts = '$allergie_nuts', all_peanut = '$allergie_peanut', all_shell = '$allergie_shell', all_celery = '$allergie_celery', all_sesame = '$allergie_celery', all_soja = '$allergie_soja', all_fish = '$allergie_fish', all_mollusks = '$allergie_mollusks', all_sulfur = '$allergie_sulfur', str_all = '$allergie_string' WHERE reservering_id = '$reservering_id'";
        $resultUpdate = mysqli_query($db, $queryUpdate); //or die('Error: ' . mysqli_error($db) . ' with query ' . $queryUpdate);
        if ($resultUpdate) {
            header('Location: ../bevestiging-reservatie');
            exit;
        } else {
            $errors['general'] = 'Er is helaas iets fout gegaan, probeer het later opnieuw.';
        }
    }
}
if (isset($_POST['back'])) {
    if (isset($_SESSION['canChangeReservation'])) {
        header('Location: ../index.php?edit=1');
        exit;
    } else {
        header('Location: ../');
        exit;
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
initializeHead('..', 'Controleren reservering bij Rasa Senang', true, false, false, false);
require_once "../includes/basic-elements/topBar.php";

if (isset($_GET) && isset($_SESSION['canChangeReservation'])) {
    if ($_SESSION['canChangeReservation']['load_check_page'] == "false") {
        initializeTopBar('..', '../wijzigen-reservering');
    } else {
        initializeTopBar('..', '../index.php?edit=1');
    }
} elseif (isset($_SESSION['canChangeReservation'])) {
    initializeTopBar('..', '../index.php?edit=1');
} else {
    initializeTopBar('..', '../');
}

require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
    <main class="content-wrap">
        <header>
            <h1><?php if (isset($_SESSION['canChangeReservation'])) { ?> Reservering Wijzigen <?php } else { ?> Reservering Plaatsen <?php } ?></h1>
            <h3><?php if (isset($_SESSION['canChangeReservation'])) {
                    if ($_SESSION['canChangeReservation']['load_check_page'] == "false" && isset($_GET)) { ?> We hebben de onderstaande informatie gevonden:
                    <?php } elseif ($_SESSION['canChangeReservation']['load_check_page'] == "true") { ?> Controleer hieronder de aangepaste informatie:
                    <?php }
                } else { ?> Controleer hieronder de ingevulde informatie: <?php } ?>
            </h3>
            <?php if (isset($errors['general'])) { ?>
                <h3 class="errors"><?= $errors['general'] ?></h3>
            <?php } ?>
        </header>
        <form action="" method="post">
            <div class="details">
                <div class="flexDetails">
                    <div class="labelDetails">Datum:</div>
                    <div> <?= date("d/m/Y", strtotime($date)) ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Aanvangstijd:</div>
                    <div> <?= htmlentities(date("H:i", strtotime($time))); ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Aantal gasten:</div>
                    <div> <?= htmlentities($people); ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Naam:</div>
                    <div> <?= htmlentities($name); ?> </div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">E-mailadres:</div>
                    <div> <?= htmlentities($emailadres); ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Telefoonnummer:</div>
                    <div> <?= htmlentities($phonenumber); ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Allergieën/Voedselwensen:</div>
                    <div><?= htmlentities($allergie_string) ?></div>
                </div>
                <div class="flexDetails">
                    <div class="labelDetails">Opmerkingen:</div>
                    <div><?php if ($comments == '') {
                            echo "Niet van toepassing.";
                        } else {
                            echo htmlentities(htmlspecialchars_decode($comments));
                        } ?></div>
                </div>
            </div>
            <?php if (htmlentities($allergie_string) !== "Niet van toepassing." && !isset($_SESSION['loggedInUser'])) { ?> <p class="errors">Let op! Wij
                gaan zorgvuldig om met uw voedselallergie, helaas kunnen wij kruisbesmetting niet 100%
                uitsluiten.</p> <?php } elseif (htmlentities($allergie_string) !== "Niet van toepassing.") { ?>
                <p class="errors">Let op! Attendeer de gast dat wij zorgvuldig omgaan met zijn of haar voedselallergie, en dat
                    wij kruisbesmetting niet 100% kunnen uitsluiten.</p>
            <?php } ?>
            <div class="flexButtons">
                <div>
                    <div class="data-submit">
                        <?php if (isset($_SESSION['canChangeReservation'])) {
                            if ($_SESSION['canChangeReservation']['load_check_page'] == "false") { ?>
                                <input type="submit" name="back" value="Wijzigen"/>
                            <?php } else { ?> <input type="submit" name="back" value="Terug"/>
                            <?php }
                        } else { ?> <input type="submit" name="back" value="Terug"/> <?php } ?>
                    </div>
                </div>
                <?php if (isset($_SESSION['canChangeReservation'])) {
                    if ($_SESSION['canChangeReservation']['load_check_page'] == "false") { ?>
                        <div class="data-submit-guest">
                            <button type="button" data-modal-target="#modal">Verwijderen</button>
                        </div>
                        <div class="modal" id="modal" <?php if (isset($_GET['error']) && $_GET['error'] !== '') {?>style="transition: none" <?php }?>>
                            <div class="modal-header">
                                <div class="title"> Weet u zeker dat u deze reservering wilt verwijderen?</div>
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
                                            } else {
                                                echo "Let op: deze actie is permanent!";
                                            }
                                        } else {
                                            echo "Let op: deze actie is permanent!";
                                        } ?></p>
                                </div>
                                <form method="post" action="">
                                    <div class="modalAlignCenter">
                                        <div class="date-submit-div">
                                            <input class="date-submit" type="submit" name="submitDelete"
                                                   value="Verwijderen"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal" id="modal">
                            <div class="modal-header">
                                <div class="title"> Weet u zeker dat u deze reservering wilt verwijderen?</div>
                                <button data-close-button class="close-button">&times;</button>
                            </div>
                            <div class="modal-body">
                                <img src="../data/icon-general/bin-red.png"> <br>
                                <p>Let op: dit kan niet ongedaan worden.</p>
                                <form action="" method="post">
                                    <div class="date-submit-div">
                                        <input class="date-submit" type="submit" name="submitDeletion"
                                               value="Verwijderen"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="data-submit">
                            <input type="submit" name="submit" value="Bevestigen"/>
                        </div> <?php }
                } else { ?>
                    <div class="data-submit">
                        <input type="submit" name="submit" value="Bevestigen"/>
                    </div> <?php } ?>
            </div>
        </form>
    </main>
    <?php require_once('../includes/basic-elements/footer.php');
    initializeFooter('..'); ?>