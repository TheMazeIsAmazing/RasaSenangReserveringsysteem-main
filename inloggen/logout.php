<?php
require_once "../includes/head.php";
oneDotOrMoreHead('..', 'Uitloggen');
session_start();
session_destroy();
header("Location: ../inloggen/index.php?error=logoutSuccessful");
exit;

