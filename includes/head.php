<!doctype html>
<html lang="nl">
<head>
<?php function oneDotOrMoreHead($dotsString, $title, $modalScript, $topArrow){ ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Via deze website kunt u gemakkelijk reserveren bij de Rasa Senang.">
    <meta property="og:url" content="https://stud.hosted.hr.nl/1028473/RasaSenangReserveringsysteem-main/"/>
    <meta property="og:title" content="Reserveren bij Rasa Senang"/>
    <meta property="og:description" content="Via deze website kunt u gemakkelijk reserveren bij de Rasa Senang."/>
    <meta property="og:image" content="https://stud.hosted.hr.nl/1028473/RasaSenangReserveringsysteem-main/data/logo.jpg"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $dotsString ?>/data/site-icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $dotsString ?>/data/site-icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $dotsString ?>/data/site-icons/favicon-16x16.png">
    <link rel="manifest" href="<?= $dotsString ?>/data/site-icons/site.webmanifest">
    <link rel="mask-icon" href="<?= $dotsString ?>/data/site-icons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?= $dotsString ?>/data/site-icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#e6e6e6">
    <meta name="msapplication-config" content="<?= $dotsString ?>/data/site-icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?= $dotsString ?>/includes/style.css">
    <title><?= $title ?></title>
    <script defer src="<?= $dotsString ?>/includes/side-menu-scripts.js"></script>
<?php if ($modalScript == true) { ?>
    <script defer src="<?= $dotsString ?>/includes/modal-scripts.js"></script>
<?php } ?>
<?php if ($topArrow == true) { ?>
     <script defer src="<?= $dotsString ?>/includes/top-arrow-scripts.js"></script>
<?php } ?>
<?php if (isset($_SESSION['loggedInUser'])) { ?>
      <script defer src="<?= $dotsString ?>/includes/top-bar-clock-scripts.js"></script>
<?php } ?>
</head>
<body>
<?php if ($modalScript == true) { ?>
    <div class="overlayModal"></div>
<?php } ?>
    <?php } ?>