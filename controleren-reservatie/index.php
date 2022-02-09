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

//include basic pages such as navbar and footer.
require_once "../includes/footer.php";
require_once "../includes/head.php";
oneDotOrMoreHead('..');
require_once "../includes/sideNav.php";
oneDotOrMoreNav('..');

if (isset($_POST['submitDeletion'])) {
    $reservationIdQuery = mysqli_escape_string($db, $_SESSION['canChangeReservation']['reservering_id']);
    $_SESSION['canChangeReservation']['deletion'] = "true";
    $currentTime = date("Y-m-d H:i:s");
    $userDelete = "guest";
    $deleteMail = "false";
    $deleteQuery = "UPDATE reserveringen SET  date_updated_reservation = '$currentTime', deleted_by_user = '$userDelete', delete_mail_sent = '$deleteMail' WHERE reservering_id = '$reservationIdQuery'";
    $result3 = mysqli_query($db, $deleteQuery); //or die('Error: ' . mysqli_error($db) . ' with query ' . $deleteQuery);
    if ($result3) {
        mysqli_close($db);
        $_SESSION['deletedReservation'] = "true";
        $_SESSION['reservation'] = "true";
        header('Location: ../bevestiging-reservatie');
        exit;
    } else {
        $errors['general'] = 'Er is helaas iets fout gegaan, probeer het later opnieuw.';
        mysqli_close($db);
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
        $query = "INSERT INTO `reserveringen`(date, start_time, amount_people, full_name, emailadres, phonenumber, date_placed_reservation, comments, unique_code, date_updated_reservation, all_egg, all_gluten, all_lupine, all_milk, all_mustard, all_nuts, all_peanut, all_shell, all_celery, all_sesame, all_soja, all_fish, all_mollusks, all_sulfur, str_all, mail_str_date, mail_str_time) VALUES ('$date', '$time', '$people', '$name', '$emailadres', '$phonenumber', '$currentTime', '$comments', '$randomNumber', '$currentTime', '$allergie_egg', '$allergie_gluten', '$allergie_lupine', '$allergie_milk', '$allergie_mustard', '$allergie_nuts', '$allergie_peanut', '$allergie_shell', '$allergie_celery', '$allergie_sesame', '$allergie_soja', '$allergie_fish', '$allergie_mollusks', '$allergie_sulfur', '$allergie_string', '$date', '$time')";
        $result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

        if ($result) {
            mysqli_close($db);
            if (isset($_SESSION['loggedInUser'])) {
                header('Location: ../overzicht-reserveringen');
                exit;
            } else {
                header('Location: ../bevestiging-reservatie');
                exit;
            }
        } else {
            $errors['general'] = 'Er is helaas iets fout gegaan, probeer het later opnieuw.';
            mysqli_close($db);
        }

    } else {
        $reservering_id = $_SESSION['canChangeReservation']['reservering_id'];
        $query = "UPDATE `reserveringen` SET date = '$date', mail_str_date = '$date', start_time = '$time', mail_str_time = '$time', amount_people = '$people', full_name = '$name', emailadres = '$emailadres', phonenumber = '$phonenumber', comments = '$comments', date_updated_reservation = '$currentTime', all_egg = '$allergie_egg', all_gluten = '$allergie_gluten', all_lupine = '$allergie_lupine', all_milk = '$allergie_milk', all_mustard = '$allergie_mustard', all_nuts = '$allergie_nuts', all_peanut = '$allergie_peanut', all_shell = '$allergie_shell', all_celery = '$allergie_celery', all_sesame = '$allergie_celery', all_soja = '$allergie_soja', all_fish = '$allergie_fish', all_mollusks = '$allergie_mollusks', all_sulfur = '$allergie_sulfur', str_all = '$allergie_string' WHERE reservering_id = '$reservering_id'";
        $result = mysqli_query($db, $query) or die('Error: ' . mysqli_error($db) . ' with query ' . $query);
        if ($result) {
            mysqli_close($db);
            header('Location: ../bevestiging-reservatie');
            exit;
        } else {
            $errors['general'] = 'Er is helaas iets fout gegaan, probeer het later opnieuw.';
            mysqli_close($db);
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
?>
<!doctype html>
<html lang="nl">
<head>
    <title>Controleren bij Rasa Senang</title>
</head>
<body>
<header class="topBar">
    <button class="ham">
        <img src="../data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="../data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <?php if (isset($_GET) && isset($_SESSION['canChangeReservation'])) {
    if ($_SESSION['canChangeReservation']['load_check_page'] == "false") { ?>
    <a href="../wijzigen-reservering">
        <?php } else { ?>
        <a href="../index.php?edit=1">
            <?php }
            } elseif (isset($_SESSION['canChangeReservation'])) { ?>
            <a href="../index.php?edit=1">
                <?php } else { ?>
                <a href="../">
                    <?php } ?>
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
            <h1><?php if (isset($_SESSION['canChangeReservation'])) { ?> Reservering Wijzigen <?php } else { ?> Reserveren <?php } ?></h1>
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
            <ul class="details">
                <li>Datum: <?= htmlentities(date("d/m/Y", strtotime($date))); ?></li>
                <li>Aanvangstijd: <?= htmlentities(date("H:i", strtotime($time))); ?></li>
                <li>Aantal gasten: <?= htmlentities($people); ?></li>
                <li>Naam: <?= htmlentities($name); ?></li>
                <li>E-mailadres: <?= htmlentities($emailadres); ?></li>
                <li>Telefoonnummer: <?= htmlentities($phonenumber); ?></li>
                <li>Allergieën/Voedselwensen: <?= htmlentities($allergie_string) ?> </li>
                <li>Opmerkingen: <?php if ($comments == '') {
                        echo "Niet van toepassing.";
                    } else {
                        echo htmlentities(htmlspecialchars_decode($comments));
                    } ?></li>
            </ul>
            <?php if (htmlentities($allergie_string) !== "Niet van toepassing.") { ?> <p class="errors">Let op! Wij
                gaan zorgvuldig om met uw voedselallergie, helaas kunnen wij kruisbesmetting niet 100%
                uitsluiten.</p> <?php } ?>
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
                        <div class="modal" id="modal">
                            <div class="modal-header">
                                <div class="title"> Weet u zeker dat u deze reservering wilt verwijderen?</div>
                                <button data-close-button class="close-button">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="modalAlignCenter">
                                    <img src="../data/icon-general/bin-red.png">
                                </div>
                                <div class="modalAlignCenter">
                                    <p class="errors"> <?php if (isset($errors['general']) && $errors['general'] !== '') {
                                            echo $errors['general'];
                                        } else {
                                            echo "Let op: deze actie is permanent!";
                                        } ?></p>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $reservationID; ?>"
                                      method="post">
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
    <footer>
        <section> <?= $footer ?>  </section>
    </footer>
</div>
</body>
</html>
