<!DOCTYPE html>
<html lang="nl">
<head id="head">
<?php function initializeHead($dotsString, $title, $modalScript, $topArrow, $createDaySettingScript, $registerScript) { ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Via deze website kunt u gemakkelijk reserveren bij de Rasa Senang.">
    <meta property="og:url" content="https://reserveren.rasasenang.com/"/>
    <meta property="og:title" content="Reserveren bij Rasa Senang"/>
    <meta property="og:description" content="Via deze website kunt u gemakkelijk reserveren bij de Rasa Senang."/>
    <meta property="og:image" content="https://reserveren.rasasenang.com/data/logo-hi-rez.jpg"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="msapplication-TileColor" content="#e6e6e6">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?= $dotsString ?>/includes/style.css">
<!--    <link rel="stylesheet" href="--><?//= $dotsString ?><!--/includes/essential.css">-->
<!--    <link id="style-rel-head" rel="stylesheet" href="--><?//= $dotsString ?><!--">-->
    <title><?= $title ?></title>
    <script defer src="<?= $dotsString ?>/includes/scripts/side-menu-scripts.js"></script>
<!--    <script async src="--><?//= $dotsString ?><!--/includes/scripts/dark-mode-scripts.js"></script>-->
<?php if ($registerScript == true) { ?>
    <script defer src="<?= $dotsString ?>/includes/scripts/register-page-scripts.js"></script>
    <script src="<?= $dotsString ?>/includes/scripts/zxcvbn.js"></script>
<?php } ?>
<?php if ($modalScript == true) { ?>
    <script defer src="<?= $dotsString ?>/includes/scripts/modal-scripts.js"></script>
<?php } ?>
<?php if ($createDaySettingScript == true) { ?>
    <script defer src="<?= $dotsString ?>/includes/scripts/day-settings-create-edit-page-scripts.js"></script>
<?php } ?>
<?php if ($topArrow == true) { ?>
     <script defer src="<?= $dotsString ?>/includes/scripts/top-arrow-scripts.js"></script>
<?php } ?>
<?php if (isset($_SESSION['loggedInUser'])) { ?>
      <script defer src="<?= $dotsString ?>/includes/scripts/top-bar-clock-scripts.js"></script>
<?php } ?>
</head>
<body>
<?php if ($modalScript == true) { ?>
    <div class="overlayModal"></div>
<?php } ?>
    <?php } ?>