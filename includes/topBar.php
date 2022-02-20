<header class="topBar">
    <?php function oneDotOrMoreTopBar($dotsString, $backRedirect)
    { ?><button class="ham">
        <img src="<?= $dotsString ?>/data/icon-general/menu.png" alt="Open Zijmenu">
    </button>
    <img class="logo" src="<?= $dotsString ?>/data/logo-half-transparent.png" alt="Logo Rasa Senang">
    <a href="<?= $backRedirect?>">
        <button class="back">
            <?php if ($backRedirect == '../inloggen/logout.php') {?><img src="../data/icon-general/log-out.png" alt="Uitloggen">
            <?php } else { ?><img src="<?= $dotsString ?>/data/icon-general/back.png" alt="Terug naar Beginpagina"><?php } ?>
        </button>
    </a>
</header>
<?php } ?>