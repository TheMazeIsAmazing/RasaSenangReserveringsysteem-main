<?php
session_start();

$canChangeReservation = false;

//Require database in this file
require_once './includes/database.php';
/** @var mysqli $db */

if ((isset($_GET['edit']) && $_GET['edit'] !== '1') || (isset($_GET['edit']) && !isset($_SESSION['canChangeReservation'])) || (!isset($_GET['edit']) && isset($_SESSION['canChangeReservation']))) {
    if (isset($_SESSION['canChangeReservation'])) {
        unset($_SESSION['canChangeReservation']);
    }
    if (isset($_GET['edit'])) {
        unset($_GET['edit']);
    }
    header('location: ./');
    exit;
}

//set default value of all allergies to be negative
$allergie_egg = "off";
$allergie_gluten = "off";
$allergie_lupine = "off";
$allergie_milk = "off";
$allergie_mustard = "off";
$allergie_nuts = "off";
$allergie_peanut = "off";
$allergie_shell = "off";
$allergie_celery = "off";
$allergie_sesame = "off";
$allergie_soja = "off";
$allergie_fish = "off";
$allergie_mollusks = "off";
$allergie_sulfur = "off";

//set default value of time to be 17:00
$time = "17:00";

if (isset($_POST['submit'])) {
    // 'Post back' with the data from the form.
    $date = mysqli_escape_string($db, test_input($_POST['date']));
    $time = mysqli_escape_string($db, test_input($_POST['time']));
    $people = mysqli_escape_string($db, test_input($_POST['people']));
    $name = mysqli_escape_string($db, test_input($_POST['name']));
    $emailadres = mysqli_escape_string($db, test_input($_POST['emailadres']));
    $phonenumber = mysqli_escape_string($db, test_input($_POST['phonenumber']));
    $comments = mysqli_escape_string($db, test_input($_POST['comments']));

    if (isset($_POST['allergie-egg'])) {
        $allergie_egg = "on";
    }

    if (isset($_POST['allergie-gluten'])) {
        $allergie_gluten = "on";
    }

    if (isset($_POST['allergie-lupine'])) {
        $allergie_lupine = "on";
    }

    if (isset($_POST['allergie-milk'])) {
        $allergie_milk = "on";
    }
    if (isset($_POST['allergie-mustard'])) {
        $allergie_mustard = "on";
    }

    if (isset($_POST['allergie-nuts'])) {
        $allergie_nuts = "on";
    }

    if (isset($_POST['allergie-peanut'])) {
        $allergie_peanut = "on";
    }

    if (isset($_POST['allergie-shell'])) {
        $allergie_shell = "on";
    }

    if (isset($_POST['allergie-celery'])) {
        $allergie_celery = "on";
    }

    if (isset($_POST['allergie-sesame'])) {
        $allergie_sesame = "on";
    }

    if (isset($_POST['allergie-soja'])) {
        $allergie_soja = "on";
    }

    if (isset($_POST['allergie-fish'])) {
        $allergie_fish = "on";
    }

    if (isset($_POST['allergie-mollusks'])) {
        $allergie_mollusks = "on";
    }

    if (isset($_POST['allergie-sulfur'])) {
        $allergie_sulfur = "on";
    }

    date_default_timezone_set("Europe/Amsterdam");


    //initiate errors array
    $errors = [];

    //if certain criteria have been met, place it in the array
    //Will display only one message if groups are large enough, even if other criteria have been met
    if ($people >= 50 && !isset($_SESSION['loggedInUser'])) {
        $errors['general'] = 'Voor reserveringen van 50 of meer gasten raden wij aan om telefonisch contact op te nemen met het restaurant. (078-6511160)';
    } else {
        //check if date is empty, if that's the case add message to errors array
        if ($date == '') {
            $errors['date'] = 'Het veld: Datum mag niet leeg zijn.';
        } //Check if current time is past 16:00, and the desired day is the same as the same day
        elseif (date("d m Y", strtotime($date)) == date("d m Y") && date("H i") >= date("H i", strtotime("16:00")) && !isset($_SESSION['loggedInUser'])) {
            $errors['date'] = 'Voor reserveringen op dezelfde dag na 16:00 moet u het restaurant bellen. (078-6511160)';
        } //Check if chosen date is in the future
        //Check if current time is before 16:00 or an employee has signed in.
        elseif ((date("Y m d", strtotime($date)) < date("Y m d") && date("H i") < date("H i", strtotime("16:00"))) || (isset($_SESSION['loggedInUser']) && date("Y m d", strtotime($date)) < date("Y m d") && date("H i") >= date("H i", strtotime("16:00")))) {
            $errors['date'] = 'U kunt alleen op een datum in de toekomst reserveren. U kunt dus een datum kiezen vanaf: ' . date("d-m-Y");
        } elseif (date("Y m d", strtotime($date)) < date("Y m d") && date("H i") >= date("H i", strtotime("16:00"))) {
            $errors['date'] = 'U kunt alleen op een datum in de toekomst reserveren. U kunt dus een datum kiezen vanaf: ' . date("d-m-Y", strtotime("+ 1 day"));
        }

        //check if people is empty, if that's the case add message to errors array
        if ($people == '') {
            $errors['people'] = 'Het veld: Aantal Gasten mag niet leeg zijn.';
        }

        //check if name is empty, if that's the case add message to errors array
        if ($name == '') {
            $errors['name'] = 'Het veld: Naam mag niet leeg zijn.';
        }


        //check if email is empty, if that's the case add message to errors array
        if ($emailadres == '') {
            $errors['emailadres'] = 'Het veld: E-mailadres mag niet leeg zijn.';

            //add code that checks character amount (at least 8
        } elseif (strlen($emailadres) < 8) {
            $errors['emailadres'] = 'Het veld: E-mailadres moet minimaal 8 karakters bevatten';

            //add code that checks if @ and . are present
        } else {
            if (!filter_var($emailadres, FILTER_VALIDATE_EMAIL)) {
                $errors['emailadres'] = 'Uw e-mailadres is niet correct ingevuld';
            }
        }

        //check if phone number is empty, if that's the case add message to errors array
        if ($phonenumber == '') {
            $errors['phonenumber'] = 'Het veld: Telefoonnummer mag niet leeg zijn.';
        } elseif (strlen($phonenumber) < 8 || strlen($phonenumber) > 16) {
            $errors['phonenumber'] = 'Het veld: Telefoonnummer moet tussen de 8 en 16 nummers bevatten';
        } elseif (!preg_match('/^[0-9 +-]*$/', $phonenumber)) {
            $errors['phonenumber'] = 'Uw telefoonnummer mag alleen nummers, + en - bevatten';
        }
    }

    if (empty($errors)) {
        $_SESSION['reservation'] = [
            'date' => mysqli_escape_string($db, test_input($_POST['date'])),
            'time' => mysqli_escape_string($db, test_input($_POST['time'])),
            'people' => mysqli_escape_string($db, test_input($_POST['people'])),
            'name' => mysqli_escape_string($db, test_input($_POST['name'])),
            'emailadres' => test_input($_POST['emailadres']),
            'phonenumber' => mysqli_escape_string($db, test_input($_POST['phonenumber'])),
            'comments' => mysqli_escape_string($db, test_input($_POST['comments']))
        ];

        if (isset($_POST['allergie-egg'])) {
            $_SESSION['reservation']['all_egg'] = mysqli_escape_string($db, test_input($_POST['allergie-egg']));
        }

        if (isset($_POST['allergie-gluten'])) {
            $_SESSION['reservation']['all_gluten'] = mysqli_escape_string($db, test_input($_POST['allergie-gluten']));
        }

        if (isset($_POST['allergie-lupine'])) {
            $_SESSION['reservation']['all_lupine'] = mysqli_escape_string($db, test_input($_POST['allergie-lupine']));
        }

        if (isset($_POST['allergie-milk'])) {
            $_SESSION['reservation']['all_milk'] = mysqli_escape_string($db, test_input($_POST['allergie-milk']));
        }
        if (isset($_POST['allergie-mustard'])) {
            $_SESSION['reservation']['all_mustard'] = mysqli_escape_string($db, test_input($_POST['allergie-mustard']));
        }

        if (isset($_POST['allergie-nuts'])) {
            $_SESSION['reservation']['all_nuts'] = mysqli_escape_string($db, test_input($_POST['allergie-nuts']));
        }

        if (isset($_POST['allergie-peanut'])) {
            $_SESSION['reservation']['all_peanut'] = mysqli_escape_string($db, test_input($_POST['allergie-peanut']));
        }

        if (isset($_POST['allergie-shell'])) {
            $_SESSION['reservation']['all_shell'] = mysqli_escape_string($db, test_input($_POST['allergie-shell']));
        }

        if (isset($_POST['allergie-celery'])) {
            $_SESSION['reservation']['all_celery'] = mysqli_escape_string($db, test_input($_POST['allergie-celery']));
        }

        if (isset($_POST['allergie-sesame'])) {
            $_SESSION['reservation']['all_sesame'] = mysqli_escape_string($db, test_input($_POST['allergie-sesame']));
        }

        if (isset($_POST['allergie-soja'])) {
            $_SESSION['reservation']['all_soja'] = mysqli_escape_string($db, test_input($_POST['allergie-soja']));
        }

        if (isset($_POST['allergie-fish'])) {
            $_SESSION['reservation']['all_fish'] = mysqli_escape_string($db, test_input($_POST['allergie-fish']));
        }

        if (isset($_POST['allergie-mollusks'])) {
            $_SESSION['reservation']['all_mollusks'] = mysqli_escape_string($db, test_input($_POST['allergie-mollusks']));
        }

        if (isset($_POST['allergie-sulfur'])) {
            $_SESSION['reservation']['all_sulfur'] = mysqli_escape_string($db, test_input($_POST['allergie-sulfur']));
        }
        if (isset($_SESSION['canChangeReservation'])) {
            $_SESSION['canChangeReservation']['load_check_page'] = "true";
        }
        header('Location: ./controleren-reservatie');
        exit;
    }

} elseif (isset($_SESSION['reservation'])) {
    $date = mysqli_escape_string($db, test_input($_SESSION['reservation']['date']));
    $time = mysqli_escape_string($db, test_input($_SESSION['reservation']['time']));
    $people = mysqli_escape_string($db, test_input($_SESSION['reservation']['people']));
    $name = mysqli_escape_string($db, test_input($_SESSION['reservation']['name']));
    $emailadres = mysqli_escape_string($db, test_input($_SESSION['reservation']['emailadres']));
    $phonenumber = mysqli_escape_string($db, test_input($_SESSION['reservation']['phonenumber']));
    $comments = mysqli_escape_string($db, test_input($_SESSION['reservation']['comments']));

    if (isset($_SESSION['reservation']['all_egg'])) {
        $allergie_egg = "on";
    }
    if (isset($_SESSION['reservation']['all_gluten'])) {
        $allergie_gluten = "on";
    }
    if (isset($_SESSION['reservation']['all_lupine'])) {
        $allergie_lupine = "on";
    }
    if (isset($_SESSION['reservation']['all_milk'])) {
        $allergie_milk = "on";
    }
    if (isset($_SESSION['reservation']['all_mustard'])) {
        $allergie_mustard = "on";
    }
    if (isset($_SESSION['reservation']['all_nuts'])) {
        $allergie_nuts = "on";
    }
    if (isset($_SESSION['reservation']['all_peanut'])) {
        $allergie_peanut = "on";
    }
    if (isset($_SESSION['reservation']['all_shell'])) {
        $allergie_shell = "on";
    }
    if (isset($_SESSION['reservation']['all_celery'])) {
        $allergie_celery = "on";
    }
    if (isset($_SESSION['reservation']['all_sesame'])) {
        $allergie_sesame = "on";
    }
    if (isset($_SESSION['reservation']['all_soja'])) {
        $allergie_soja = "on";
    }
    if (isset($_SESSION['reservation']['all_fish'])) {
        $allergie_fish = "on";
    }
    if (isset($_SESSION['reservation']['all_mollusks'])) {
        $allergie_mollusks = "on";
    }
    if (isset($_SESSION['reservation']['all_sulfur'])) {
        $allergie_sulfur = "on";
    }
} elseif (isset($_SESSION['canChangeReservation'])) {
    $date = mysqli_escape_string($db, test_input($_SESSION['canChangeReservation']['date']));
    $time = mysqli_escape_string($db, test_input(date("H:i", strtotime($_SESSION['canChangeReservation']['start_time']))));
    $people = mysqli_escape_string($db, test_input($_SESSION['canChangeReservation']['amount_people']));
    $name = mysqli_escape_string($db, test_input($_SESSION['canChangeReservation']['full_name']));
    $emailadres = mysqli_escape_string($db, test_input($_SESSION['canChangeReservation']['emailadres']));
    $phonenumber = mysqli_escape_string($db, test_input($_SESSION['canChangeReservation']['phonenumber']));
    $comments = mysqli_escape_string($db, test_input($_SESSION['canChangeReservation']['comments']));

    $allergie_egg = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_egg']);
    $allergie_gluten = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_gluten']);
    $allergie_lupine = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_lupine']);
    $allergie_milk = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_milk']);
    $allergie_mustard = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_mustard']);
    $allergie_nuts = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_nuts']);
    $allergie_peanut = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_peanut']);
    $allergie_shell = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_shell']);
    $allergie_celery = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_celery']);
    $allergie_sesame = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_sesame']);
    $allergie_soja = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_soja']);
    $allergie_fish = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_fish']);
    $allergie_mollusks = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_mollusks']);
    $allergie_sulfur = mysqli_escape_string($db, $_SESSION['canChangeReservation']['all_sulfur']);
}

//include basic pages such as navbar and header.
require_once "./includes/head.php";
oneDotOrMoreHead('.', 'Reserveren bij Rasa Senang', false, false);
require_once "./includes/topBar.php";
if (isset($_SESSION['canChangeReservation']) && isset($_SESSION['loggedInUser'])) {
    oneDotOrMoreTopBar('.', './overzicht-reserveringen/details.php?id=' . $_SESSION['canChangeReservation']['reservering_id']);
} elseif (isset($_SESSION['loggedInUser'])) {
    oneDotOrMoreTopBar('.', './overzicht-reserveringen');
} else {
    oneDotOrMoreTopBar('.', 'https://www.rasasenang.com/nl/');
}
require_once "./includes/sideNav.php";
oneDotOrMoreNav('.', false);
?>

<div class="page-container">
    <main class="content-wrap">
        <header>
            <?php if (isset($_SESSION['canChangeReservation']) && isset($_GET)) { ?>
                <h1>Reservering Wijzigen</h1>
            <?php } elseif (isset($_SESSION['loggedInUser'])) { ?>
                <h1>Reservering Plaatsen</h1>
            <?php } else { ?>
                <h1>Reserveren</h1>
            <?php } ?>
            <!-- Show errors if present -->
            <?php if (isset($errors['general'])) { ?>
                <h3><?= $errors['general'] ?></h3>
            <?php } ?>
        </header>
        <div class="flexRequired">
            <div>
                (Velden
            </div>
            <div>
                met
            </div>
            <div>
                een
            </div>
            <div class="errors">
                *
            </div>
            <div>
                zijn
            </div>
            <div>
                verplicht.)
            </div>
        </div>
        <section>
            <!-- From for reservations -->
            <form action="<?php /*
                if (isset($_GET)) {
                    echo htmlentities($_SERVER["PHP_SELF"]) . '?edit=1';
                } else {
                    echo htmlentities($_SERVER["PHP_SELF"]);
                }  */ ?>" method="post">
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="date">Datum </label>
                        <div class="errors">
                            *
                        </div>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Vul hier de datum in waarop u wilt reserveren.</span>
                        </div>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="date" name="date" value="<?= $date ?? '' ?>"/>
                        <span class="errors"><?= $errors['date'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="time">Aanvangstijd</label>
                        <div class="errors">
                            *
                        </div>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Vul hier in hoe laat u ongeveer in het restaurant zal zijn.</span>
                        </div>
                    </div>
                    <div class="flexInputWithErrors">
                        <select name="time">
                            <option value="16:00" <?php if ($time == "16:00") { ?> selected <?php } ?>>16:00</option>
                            <option value="16:30" <?php if ($time == "16:30") { ?> selected <?php } ?>>16:30</option>
                            <option value="17:00" <?php if ($time == "17:00") { ?> selected <?php } ?>>17:00</option>
                            <option value="17:30" <?php if ($time == "17:30") { ?> selected <?php } ?>>17:30</option>
                            <option value="18:00" <?php if ($time == "18:00") { ?> selected <?php } ?>>18:00</option>
                            <option value="18:30" <?php if ($time == "18:30") { ?> selected <?php } ?>>18:30</option>
                            <option value="19:00" <?php if ($time == "19:00") { ?> selected <?php } ?>>19:00</option>
                            <option value="19:30" <?php if ($time == "19:30") { ?> selected <?php } ?>>19:30</option>
                            <option value="20:00" <?php if ($time == "20:00") { ?> selected <?php } ?>>20:00</option>
                            <option value="20:30" <?php if ($time == "20:30") { ?> selected <?php } ?>>20:30</option>
                            <option value="21:00" <?php if ($time == "21:00") { ?> selected <?php } ?>>21:00</option>
                        </select>
                        <span class="errors"><?= $errors['time'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="people">Aantal Gasten</label>
                        <div class="errors">
                            *
                        </div>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Vul hier in voor hoeveel personen u wilt reserveren.</span>
                        </div>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="number" name="people" min="1" value="<?= $people ?? '' ?>"/>
                        <span class="errors"><?= $errors['people'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="name">Naam</label>
                        <div class="errors">
                            *
                        </div>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Vul hier in op welke naam wilt reserveren.</span>
                        </div>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="text" name="name" value="<?= $name ?? '' ?>" placeholder="Jan van Alleman"/>
                        <span class="errors"><?= $errors['name'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="emailadres">E-mailadres</label>
                        <div class="errors">
                            *
                        </div>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Vul hier uw e-mailadres in, zo kunnen wij een bevestiging sturen van uw reservering. </span>
                        </div>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="email" name="emailadres" maxlength="255"
                               value="<?= $emailadres ?? '' ?>" placeholder="jan-en-alleman@mail.nl"/>
                        <span class="errors"><?= $errors['emailadres'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="phonenumber">Telefoonnummer</label>
                        <div class="errors">
                            *
                        </div>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Vul hier uw telefoonnummer in, dan kunnen wij, mocht dit nodig zijn, gemakkelijk contact opnemen met u. </span>
                        </div>
                    </div>
                    <div class="flexInputWithErrors">
                        <input type="tel" name="phonenumber" value="<?= $phonenumber ?? '' ?>"
                               placeholder="06-12345678"/>
                        <div class="errors"><?= $errors['phonenumber'] ?? '' ?> </div>
                    </div>
                </div>
                <div class="flexLabel">
                    <label for="allergies"> Allergieën/Voedselwensen</label>
                    <div class="tooltip"><img src="./data/icon-general/information.png"> <span class="tooltiptext">Vul hier mogelijke allergieën in, dan kunnen wij hier direct rekening mee houden. Ook als u voedselwensen heeft, bijvoorbeeld omdat u vegetariër bent, kunt u dit hier doorgeven. </span>
                    </div>
                </div>
                <div class="data-field-allergies">
                    <div class="data-field">
                        <input type="checkbox" id="allergie-egg"
                               name="allergie-egg" <?php if ($allergie_egg == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-egg"><img src="./data/icon-allergie/ei.png"
                                                                               alt="Eieren"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-gluten"
                               name="allergie-gluten" <?php if ($allergie_gluten == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-gluten"><img src="./data/icon-allergie/gluten.png"
                                                                                  alt="Gluten"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-lupine"
                               name="allergie-lupine" <?php if ($allergie_lupine == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-lupine"><img src="./data/icon-allergie/lupine.png"
                                                                                  alt="Lupine"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-milk"
                               name="allergie-milk" <?php if ($allergie_milk == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-milk"><img src="./data/icon-allergie/melk.png"
                                                                                alt="Melk"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-mustard"
                               name="allergie-mustard" <?php if ($allergie_mustard == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-mustard"><img
                                    src="./data/icon-allergie/mosterd.png" alt="Mosterd"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-nuts"
                               name="allergie-nuts" <?php if ($allergie_nuts == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-nuts"><img src="./data/icon-allergie/noten.png"
                                                                                alt="Noten"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-peanut"
                               name="allergie-peanut" <?php if ($allergie_peanut == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-peanut"><img src="./data/icon-allergie/pindas.png"
                                                                                  alt="Pinda's"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-shell"
                               name="allergie-shell" <?php if ($allergie_shell == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-shell"><img src="./data/icon-allergie/schaald.png"
                                                                                 alt="Schaaldieren"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-celery"
                               name="allergie-celery" <?php if ($allergie_celery == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-celery"><img
                                    src="./data/icon-allergie/selderij.png" alt="Selderij"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-sesame"
                               name="allergie-sesame" <?php if ($allergie_sesame == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-sesame"><img
                                    src="./data/icon-allergie/sesamzaad.png" alt="Sesamzaad"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-soja"
                               name="allergie-soja" <?php if ($allergie_soja == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-soja"><img src="./data/icon-allergie/soja.png"
                                                                                alt="Soja"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-fish"
                               name="allergie-fish" <?php if ($allergie_fish == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-fish"><img src="./data/icon-allergie/vis.png"
                                                                                alt="Vis"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-mollusks"
                               name="allergie-mollusks" <?php if ($allergie_mollusks == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-mollusks"><img
                                    src="./data/icon-allergie/weekdieren.png" alt="Weekdieren"/></label>
                    </div>
                    <div class="data-field">
                        <input type="checkbox" id="allergie-sulfur"
                               name="allergie-sulfur" <?php if ($allergie_sulfur == "on") { ?> checked <?php } ?> />
                        <label class="label-for-check" for="allergie-sulfur"><img src="./data/icon-allergie/zwavel.png"
                                                                                  alt="Zwaveldioxide"/></label>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="comments">Opmerkingen </label>
                        <div class="tooltip"><img src="./data/icon-general/information.png"> <span
                                    class="tooltiptext">Mocht u opmerkingen hebben voor ons, dan kunt u dat hier invullen.</span>
                        </div>
                    </div>
                    <textarea name="comments" cols="40" rows="5"><?php if (isset($comments) && $comments !== '') {
                            echo htmlspecialchars_decode($comments);
                        } ?></textarea>
                    <span class="errors"><?= $errors['comments'] ?? '' ?></span>
                </div>
                <div class="flexButtons">
                    <div class="data-submit">
                        <input type="submit" name="submit" value="Controleren"/>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <?php require_once('./includes/footer.php');
    oneDotOrMoreFooter('.'); ?>
</div>
</body>
</html>