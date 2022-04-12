<?php function oneDotOrMoreTopBar($dotsString, $backRedirect)
{ ?>
    <?php if (isset($_SESSION['loggedInUser']))
{ ?>
    <header class="topBarEmployee">
    <button class="ham">
        <img src="<?= $dotsString ?>/data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
        <div class="logo-with-clock-middle-top-bar">
            <a href="<?= $dotsString ?>/medewerkers" class="topBarLogoRedirect">
                <img class="logo" src="<?= $dotsString ?>/data/logo-half-transparent.png" alt="Logo Rasa Senang">
            </a>
            <div id="timeDate">
                <a id="d"><?= date('d') ?></a>
                <a id="mon"><?= date('F') ?></a>,
                <a id="y"><?= date('Y') ?></a><br />
                <a id="h"><?= date('H') ?></a>:<a id="m"><?= date('i') ?></a>:<a id="s"><?= date('s') ?></a>
            </div>
        </div>
    <a href="<?= $backRedirect ?>">
         <button class="back">
            <?php if ($backRedirect == '../inloggen/logout.php') { ?><img src="<?= $dotsString ?>/data/icon-general/log-out.png" alt="Uitloggen">
            <?php } else { ?><img src="<?= $dotsString ?>/data/icon-general/back.png" alt="Terug naar Beginpagina"><?php } ?>
        </button>
    </a>
    </header>
<?php } else { ?>
    <header class="topBarCustomer">
        <button class="ham">
            <img src="<?= $dotsString ?>/data/icon-general/menu.png" alt="Open Zijmenu">
        </button>
            <a href="<?= $dotsString ?>/" class="topBarLogoRedirect">
                <img class="logo" src="<?= $dotsString ?>/data/logo-half-transparent.png" alt="Logo Rasa Senang">
            </a>
        <a href="<?= $backRedirect ?>">
            <button class="back">
                <?php if ($backRedirect == '../inloggen/logout.php') { ?><img src="<?=$dotsString?>/data/icon-general/log-out.png" alt="Uitloggen">
                <?php } else { ?><img src="<?= $dotsString ?>/data/icon-general/back.png" alt="Terug naar Beginpagina"><?php } ?>
            </button>
        </a>
    </header>
<?php } ?>
    <div class="overlay"></div>
<?php } ?>