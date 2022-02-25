<?php function oneDotOrMoreTopBar($dotsString, $backRedirect)
{ ?>
    <header class="topBar">
    <button class="ham">
        <img src="<?= $dotsString ?>/data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <?php if (isset($_SESSION['loggedInUser']))
    { ?>
    <a href="<?= $dotsString ?>/medewerkers" class="topBarLogoRedirect">
    <?php } else { ?>
    <a href="<?= $dotsString ?>/" class="topBarLogoRedirect"><?php } ?>
        <img class="logo" src="<?= $dotsString ?>/data/logo-half-transparent.png" alt="Logo Rasa Senang">
    </a>
    <a href="<?= $backRedirect ?>">
         <button class="back">
            <?php if ($backRedirect == '../inloggen/logout.php') { ?><img src="../data/icon-general/log-out.png" alt="Uitloggen">
            <?php } else { ?><img src="<?= $dotsString ?>/data/icon-general/back.png" alt="Terug naar Beginpagina"><?php } ?>
        </button>
    </a>
    </header>
    <div class="overlay"></div>
<?php } ?>