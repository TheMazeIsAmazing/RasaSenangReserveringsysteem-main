<!doctype html>
<html lang="nl">
<head>
<?php function oneDotOrMoreHead($dotsString, $title)
    { ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Via deze website kunt u gemakkelijk reserveren bij de Rasa Senang.">
    <meta property="og:url" content="https://stud.hosted.hr.nl/1028473/"/>
    <meta property="og:title" content="Reserveren bij Rasa Senang"/>
    <meta property="og:description" content="Via deze website kunt u gemakkelijk reserveren bij de Rasa Senang."/>
    <meta property="og:image" content="<?= $dotsString ?>/data/logo.jpg"/>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $dotsString ?>/data/site-icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $dotsString ?>/data/site-icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $dotsString ?>/data/site-icons/favicon-16x16.png">
    <link rel="stylesheet" href="<?= $dotsString ?>/includes/style.css">
    <title><?= $title ?></title>
    <?php if ($dotsString == '.') { ?><link rel="manifest" href="<?= $dotsString ?>/data/site-icons/indexPHP.webmanifest">
    <?php } else { ?><link rel="manifest" href="<?= $dotsString ?>/data/site-icons/site.webmanifest">
    <?php } ?><script defer src="<?= $dotsString ?>/includes/menu.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php } ?>