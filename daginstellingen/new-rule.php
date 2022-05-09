<?php
session_start();

$type = "rules";
$accept_reservations = "true";
$open_closed = "open";
$guest_limit = 120;
$reservations_limit = 20;
$times_or_timeslots = "times";
$t_1600 = "true";
$t_1630 = "true";
$t_1700 = "true";
$t_1730 = "true";
$t_1800 = "true";
$t_1830 = "true";
$t_1900 = "true";
$t_1930 = "true";
$t_2000 = "true";
$t_2030 = "true";
$t_2100 = "true";
$timeslot_1_from = date('H:i', strtotime('16:00'));
$timeslot_1_to = date('H:i', strtotime('19:00'));
$timeslot_2_from = date('H:i', strtotime('19:30'));
$timeslot_2_to = date('H:i', strtotime('22:30'));
$monday = "closed";
$tuesday = "open";
$wednesday = "open";
$thursday = "open";
$friday = "open";
$saturday = "open";
$sunday = "open";

//Require database in this file
require_once '../includes/database.php';
/**@var mysqli $db */

//May I even visit this page?
require_once "../includes/loginCheck.php";
loginCheck();
loginCheckPageSpecific('can_visit_daysettings');

if (isset($_SESSION['daySettingChange']) && $_GET['edit'] == '1') {
    $settingID = $_SESSION['daySettingChange']['setting_id'];

    $queryPull = "SELECT * FROM `day-settings` WHERE id = '$settingID'";

    $resultPull = mysqli_query($db, $queryPull); //or die('Db Error: '.mysqli_error($db).' with query: '.$queryPull);

    $daySetting = mysqli_fetch_assoc($resultPull);

    if ($daySetting['from_date'] !== '') {
        $from_date = $daySetting['from_date'];
    }
    if ($daySetting['until_date'] !== '') {
        $until_date = $daySetting['until_date'];
    }

    $type = $daySetting['type'];
    $accept_reservations = $daySetting['accept_reservations'];
    $open_closed = $daySetting['open_closed'];
    $guest_limit = $daySetting['guest_limit'];
    $reservations_limit = $daySetting['reservations_limit'];
    $monday = $daySetting['monday'];
    $tuesday = $daySetting['tuesday'];
    $wednesday = $daySetting['wednesday'];
    $thursday = $daySetting['thursday'];
    $friday = $daySetting['friday'];
    $saturday = $daySetting['saturday'];
    $sunday = $daySetting['sunday'];
    $times_or_timeslots = $daySetting['times_or_timeslots'];

    if ($times_or_timeslots == 'times') {
        $t_1600 = $daySetting['16:00'];
        $t_1630 = $daySetting['16:30'];
        $t_1700 = $daySetting['17:00'];
        $t_1730 = $daySetting['17:30'];
        $t_1800 = $daySetting['18:00'];
        $t_1830 = $daySetting['18:30'];
        $t_1900 = $daySetting['19:00'];
        $t_1930 = $daySetting['19:30'];
        $t_2000 = $daySetting['20:00'];
        $t_2030 = $daySetting['20:30'];
        $t_2100 = $daySetting['21:00'];
    } else {
        $timeslot_1_from = date('H:i', strtotime($daySetting['timeslot_1_from']));
        $timeslot_1_to = date('H:i', strtotime($daySetting['timeslot_1_to']));
        $timeslot_2_from = date('H:i', strtotime($daySetting['timeslot_2_from']));
        $timeslot_2_to = date('H:i', strtotime($daySetting['timeslot_2_to']));
    }

} else {
    unset($_GET);
    unset($_SESSION['canChangeEmployee']);
}


if (isset($_POST['submit'])) {

    if (isset($_POST['from_date'])) {
        $from_date = $_POST['from_date'];
    }

    if (isset($_POST['until_date'])) {
        $until_date = $_POST['until_date'];
    }


    if (isset($_POST['open_closed'])) {
        $open_closed = 'open';
        if (isset($_POST['accept_reservations'])) {
            $accept_reservations = 'true';
        } else {
            $accept_reservations = 'false';
        }
        $guest_limit = $_POST['guest_limit'];
        $reservations_limit = $_POST['reservations_limit'];

        if (isset($_POST['monday'])) {
            $monday = 'open';
        } else {
            $monday = 'closed';
        }


        if (isset($_POST['tuesday'])) {
            $tuesday = 'open';
        } else {
            $tuesday = 'closed';
        }

        if (isset($_POST['wednesday'])) {
            $wednesday = 'open';
        } else {
            $wednesday = 'closed';
        }

        if (isset($_POST['thursday'])) {
            $thursday = 'open';
        } else {
            $thursday = 'closed';
        }

        if (isset($_POST['friday'])) {
            $friday = 'open';
        } else {
            $friday = 'closed';
        }

        if (isset($_POST['saturday'])) {
            $saturday = 'open';
        } else {
            $saturday = 'closed';
        }

        if (isset($_POST['sunday'])) {
            $sunday = 'open';
        } else {
            $sunday = 'closed';
        }

        $times_or_timeslots = $_POST['times_or_timeslots'];

        if ($times_or_timeslots == 'times') {
            if (isset($_POST['16:00'])) {
                $t_1600 = 'true';
            } else {
                $t_1600 = 'false';
            }
            if (isset($_POST['16:30'])) {
                $t_1630 = 'true';
            } else {
                $t_1630 = 'false';
            }
            if (isset($_POST['17:00'])) {
                $t_1700 = 'true';
            } else {
                $t_1700 = 'false';
            }
            if (isset($_POST['17:30'])) {
                $t_1730 = 'true';
            } else {
                $t_1730 = 'false';
            }
            if (isset($_POST['18:00'])) {
                $t_1800 = 'true';
            } else {
                $t_1800 = 'false';
            }
            if (isset($_POST['18:30'])) {
                $t_1830 = 'true';
            } else {
                $t_1830 = 'false';
            }
            if (isset($_POST['19:00'])) {
                $t_1900 = 'true';
            } else {
                $t_1900 = 'false';
            }
            if (isset($_POST['19:30'])) {
                $t_1930 = 'true';
            } else {
                $t_1930 = 'false';
            }
            if (isset($_POST['20:00'])) {
                $t_2000 = 'true';
            } else {
                $t_2000 = 'false';
            }
            if (isset($_POST['20:30'])) {
                $t_2030 = 'true';
            } else {
                $t_2030 = 'false';
            }
            if (isset($_POST['21:00'])) {
                $t_2100 = 'true';
            } else {
                $t_2100 = 'false';
            }
        } else {
            $timeslot_1_from = date('H:i', strtotime($_POST['timeslot_1_from']));
            $timeslot_1_to = date('H:i', strtotime($_POST['timeslot_1_to']));
            $timeslot_2_from = date('H:i', strtotime($_POST['timeslot_2_from']));
            $timeslot_2_to = date('H:i', strtotime($_POST['timeslot_2_to']));
        }
    } else {
        $open_closed = 'closed';
    }

    $errors = [];

if (!isset($_SESSION['daySettingChange'])) {
    if (!isset($_POST['from_date']) || $_POST['from_date'] == '') {
        $errors['from_date'] = 'Voer de datum in vanaf wanneer deze regel in gaat.';
    }

    if (!isset($_POST['until_date']) || $_POST['until_date'] == '') {
        $errors['until_date'] = 'Voer de datum in tot wanneer deze regel geldt.';
    }
}

    if (isset($_POST['open_closed'])) {
        if (!isset($_POST['guest_limit']) || $_POST['guest_limit'] == '') {
            $errors['guest_limit'] = 'Vul een limiet van de hoeveelheid gasten in.';
        }

        if (!isset($_POST['reservations_limit']) || $_POST['reservations_limit'] == '') {
            $errors['reservations_limit'] = 'Vul een limiet van de hoeveelheid reserveringen in.';
        }

        if ($_POST['times_or_timeslots'] == 'timeslots') {
            if (!isset($_POST['timeslot_1_from']) || $_POST['timeslot_1_from'] == '') {
                $errors['timeslot_1_from'] = 'Voer in vanaf hoe laat het eerste tijdslot is.';
            }

            if (!isset($_POST['timeslot_1_to']) || $_POST['timeslot_1_to'] == '') {
                $errors['timeslot_1_to'] = 'Voer in tot hoe laat het eerste tijdslot is.';
            }

            if (!isset($_POST['timeslot_2_from']) || $_POST['timeslot_2_from'] == '') {
                $errors['timeslot_2_from'] = 'Voer in vanaf hoe laat het tweede tijdslot is.';
            }

            if (!isset($_POST['timeslot_2_to']) || $_POST['timeslot_2_to'] == '') {
                $errors['timeslot_2_to'] = 'Voer in tot hoe laat het tweede tijdslot is.';
            }
        }
    }

    if (empty($errors)) {
        if (isset($_SESSION['daySettingChange']) && $_GET['edit'] == '1') {
            if ($daySetting['from_date'] == '' && $daySetting['until_date'] == '') {
                $queryUpdate = "UPDATE `day-settings` SET `accept_reservations` = '$accept_reservations', `open_closed` = '$open_closed', `guest_limit` = '$guest_limit', `reservations_limit` = '$reservations_limit', `times_or_timeslots` = '$times_or_timeslots', `16:00` = '$t_1600', `16:30` = '$t_1630', `17:00` = '$t_1700', `17:30` = '$t_1730', `18:00` = '$t_1800', `18:30` = '$t_1830', `19:00` = '$t_1900', `19:30` = '$t_1930', `20:00` = '$t_2000', `20:30` = '$t_2030', `21:00` = '$t_2100', `timeslot_1_from` = '$timeslot_1_from', `timeslot_1_to` = '$timeslot_1_to', `timeslot_2_from` = '$timeslot_2_from', `timeslot_2_to` = '$timeslot_2_to', `monday` = '$monday', `tuesday` = '$tuesday', `wednesday` = '$wednesday', `thursday` = '$thursday', `friday` = '$friday', `saturday` = '$saturday', `sunday` = '$sunday' WHERE `id` = '$settingID';";
            } else {
                $queryUpdate = "UPDATE `day-settings` SET `accept_reservations` = '$accept_reservations', `open_closed` = '$open_closed', `from_date` = '$from_date', `until_date` = '$until_date', `guest_limit` = '$guest_limit', `reservations_limit` = '$reservations_limit', `times_or_timeslots` = '$times_or_timeslots', `16:00` = '$t_1600', `16:30` = '$t_1630', `17:00` = '$t_1700', `17:30` = '$t_1730', `18:00` = '$t_1800', `18:30` = '$t_1830', `19:00` = '$t_1900', `19:30` = '$t_1930', `20:00` = '$t_2000', `20:30` = '$t_2030', `21:00` = '$t_2100', `timeslot_1_from` = '$timeslot_1_from', `timeslot_1_to` = '$timeslot_1_to', `timeslot_2_from` = '$timeslot_2_from', `timeslot_2_to` = '$timeslot_2_to', `monday` = '$monday', `tuesday` = '$tuesday', `wednesday` = '$wednesday', `thursday` = '$thursday', `friday` = '$friday', `saturday` = '$saturday', `sunday` = '$sunday' WHERE `id` = '$settingID';";
            }

            $resultUpdate = mysqli_query($db, $queryUpdate); //or die('Db Error: ' . mysqli_error($db) . ' with query: ' . $queryUpdate);
            mysqli_close($db);
            if ($resultUpdate) {
                header('Location: ./details.php?id=' . $settingID);
                exit;
            }
        } else {

            $queryCreate = "INSERT INTO `day-settings` (`type`, `accept_reservations`, `open_closed`, `from_date`, `until_date`, `guest_limit`, `reservations_limit`, `times_or_timeslots`, `16:00`, `16:30`, `17:00`, `17:30`, `18:00`, `18:30`, `19:00`, `19:30`, `20:00`, `20:30`, `21:00`, `timeslot_1_from`, `timeslot_1_to`, `timeslot_2_from`, `timeslot_2_to`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`) VALUES ('$type', '$accept_reservations', '$open_closed', '$from_date', '$until_date', '$guest_limit', '$reservations_limit', '$times_or_timeslots', '$t_1600', '$t_1630', '$t_1700', '$t_1730', '$t_1800', '$t_1830', '$t_1900', '$t_1930', '$t_2000', '$t_2030', '$t_2100', '$timeslot_1_from', '$timeslot_1_to', '$timeslot_2_from', '$timeslot_2_to', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday');";

            $resultCreate = mysqli_query($db, $queryCreate); //or die('Db Error: '.mysqli_error($db).' with query: '.$queryCreate);
            mysqli_close($db);
            if ($resultCreate) {
                header('Location: ./regels.php');
                exit;
            }
        }
    }
}

//include basic pages such as navbar and header.
require_once "../includes/basic-elements/head.php";
if (isset($_SESSION['daySettingChange'])) {
    initializeHead('..', 'Daginstelling wijzigen bij Rasa Senang', false, false, true);
} else {
    initializeHead('..', 'Nieuwe Daginstelling voor Rasa Senang', false, false, true);
}
require_once "../includes/basic-elements/topBar.php";
initializeTopBar('..', './regels.php');
require_once "../includes/basic-elements/sideNav.php";
initializeSideNav('..', false);
?>
<main class="content-wrap">
    <header>
        <?php if (isset($_SESSION['daySettingChange'])) { ?>
            <h1>Daginstelling wijzigen</h1>
        <?php } else { ?>
            <h1>Nieuwe Daginstelling maken</h1>
        <?php } ?>
    </header>
    <form action="" method="post" class="daySettingsCreate">
        <?php if ($type !== 'general') { ?>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="from_date" class="loginLabel">Vanaf Datum:</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="date" name="from_date" value="<?= $from_date ?? '' ?>"/>
                    <span class="errors"><?= $errors['from_date'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="until_date" class="loginLabel">Tot en Met Datum:</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="date" name="until_date" value="<?= $until_date ?? '' ?>"/>
                    <span class="errors"><?= $errors['until_date'] ?? '' ?></span>
                </div>
            </div>
        <?php } ?>
        <div class="data-field">
            <div class="flexLabel">
                <label for="open_closed">Restaurant Geopend:</label>
            </div>
            <input type="checkbox"
                   name="open_closed"
                   id="restaurant_opened_checkbox" <?php if ($open_closed == "open") { ?> checked <?php } ?> />
        </div>
        <div id="hide-if-restaurant-closed">
            <div class="data-field">
                <div class="flexLabel">
                    <label for="accept_reservations">Accepteer Reserveringen:</label>
                </div>
                <input type="checkbox"
                       name="accept_reservations" <?php if ($accept_reservations == "true") { ?> checked <?php } ?> />
            </div>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="guest_limit" class="loginLabel">Maximaal aantal gasten:</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="number" name="guest_limit" value="<?= $guest_limit ?? '' ?>"/>
                    <span class="errors"><?= $errors['guest_limit'] ?? '' ?></span>
                </div>
            </div>
            <div class="data-field">
                <div class="flexLabel">
                    <label for="reservations_limit" class="loginLabel">Maximaal aantal reserveringen:</label>
                </div>
                <div class="flexInputWithErrors">
                    <input type="number" name="reservations_limit" value="<?= $reservations_limit ?? '' ?>"/>
                    <span class="errors"><?= $errors['reservations_limit'] ?? '' ?></span>
                </div>
            </div>
            <div style="display: none">
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="times_or_timeslots">Aanvangstijden of Tijdsloten:</label>
                    </div>
                    <select name="times_or_timeslots" id="times_or_timeslots">
                        <option value="times" <?php if ($times_or_timeslots == "times") { ?> selected <?php } ?>>
                            Aanvangstijden
                        </option>
                        <option value="timeslots" <?php if ($times_or_timeslots == "timeslots") { ?> selected <?php } ?>>
                            Tijdsloten
                        </option>
                    </select>
                </div>
            </div>
            <div class="times" style="display: none" id="hiddenIfTimeslotsIsSelected">
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="accept_reservations">Aanvangstijden:</label>
                    </div>
                    <div class="verticalFlex">
                        <div class="verticalFlexItem">
                            <label for="16:00">16:00</label>
                            <input type="checkbox"
                                   name="16:00" <?php if ($t_1600 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="16:30">16:30</label>
                            <input type="checkbox"
                                   name="16:30" <?php if ($t_1630 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="17:00">17:00</label>
                            <input type="checkbox"
                                   name="17:00" <?php if ($t_1700 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="17:30">17:30</label>
                            <input type="checkbox"
                                   name="17:30" <?php if ($t_1730 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="18:00">18:00</label>
                            <input type="checkbox"
                                   name="18:00" <?php if ($t_1800 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="18:30">18:30</label>
                            <input type="checkbox"
                                   name="18:30" <?php if ($t_1830 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="19:00">19:00</label>
                            <input type="checkbox"
                                   name="19:00" <?php if ($t_1900 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="19:30">19:30</label>
                            <input type="checkbox"
                                   name="19:30" <?php if ($t_1930 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="20:00">20:00</label>
                            <input type="checkbox"
                                   name="20:00" <?php if ($t_2000 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="20:30">20:30</label>
                            <input type="checkbox"
                                   name="20:30" <?php if ($t_2030 == "true") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="21:00">21:00</label>
                            <input type="checkbox"
                                   name="21:00" <?php if ($t_2100 == "true") { ?> checked <?php } ?> />
                        </div>
                    </div>
                </div>
            </div>
            <div class="timeslots" style="display: none" id="hiddenIfTimesIsSelected">
                <label for="accept_reservations">Tijdsloten:</label>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="timeslot_1">Tijdslot 1</label>
                    </div>
                    <label for="timeslot_1_from">Vanaf</label>
                    <div class="flexInputWithErrors">
                        <input type="time"
                               name="timeslot_1_from" value="<?= $timeslot_1_from ?? '' ?>"/>
                        <span class="errors"><?= $errors['timeslot_1_from'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel"></div>
                    <label for="timeslot_1_to">Tot</label>
                    <div class="flexInputWithErrors">
                        <input type="time"
                               name="timeslot_1_to" value="<?= $timeslot_1_to ?? '' ?>"/>
                        <span class="errors"><?= $errors['timeslot_1_to'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="timeslot_2">Tijdslot 2</label>
                    </div>
                    <label for="timeslot_2_from">Vanaf</label>
                    <div class="flexInputWithErrors">
                        <input type="time"
                               name="timeslot_2_from" value="<?= $timeslot_2_from ?? '' ?>"/>
                        <span class="errors"><?= $errors['timeslot_2_from'] ?? '' ?></span>
                    </div>
                </div>
                <div class="data-field">
                    <div class="flexLabel"></div>
                    <label for="timeslot_2_to">Tot</label>
                    <div class="flexInputWithErrors">
                        <input type="time"
                               name="timeslot_2_to" value="<?= $timeslot_2_to ?? '' ?>"/>
                        <span class="errors"><?= $errors['timeslot_2_to'] ?? '' ?></span>
                    </div>
                </div>
            </div>
            <div class="days">
                <div class="data-field">
                    <div class="flexLabel">
                        <label for="accept_reservations">Dagen:</label>
                    </div>
                    <div class="verticalFlex">
                        <div class="verticalFlexItem">
                            <label for="monday">Maandag</label>
                            <input type="checkbox"
                                   name="monday" <?php if ($monday == "open") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="tuesday">Dinsdag</label>
                            <input type="checkbox"
                                   name="tuesday" <?php if ($tuesday == "open") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="wednesday">Woensdag</label>
                            <input type="checkbox"
                                   name="wednesday" <?php if ($wednesday == "open") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="thursday">Donderdag</label>
                            <input type="checkbox"
                                   name="thursday" <?php if ($thursday == "open") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="friday">Vrijdag</label>
                            <input type="checkbox"
                                   name="friday" <?php if ($friday == "open") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="saturday">Zaterdag</label>
                            <input type="checkbox"
                                   name="saturday" <?php if ($saturday == "open") { ?> checked <?php } ?> />
                        </div>
                        <div class="verticalFlexItem">
                            <label for="sunday">Zondag</label>
                            <input type="checkbox"
                                   name="sunday" <?php if ($sunday == "open") { ?> checked <?php } ?> />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="data-submit">
            <?php if (isset($_SESSION['daySettingChange'])) { ?>
                <input type="submit" name="submit" value="Bevestigen"/>
            <?php } else { ?>
                <input type="submit" id="preventDefault" name="submit" value="Aanmaken"/>
            <?php } ?>
        </div>
    </form>
</main>
<?php require_once('../includes/basic-elements/footer.php');
initializeFooter('..'); ?>
