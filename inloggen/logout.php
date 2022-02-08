<?php
require_once "../includes/head.php";
oneDotOrMoreHead('..');
session_start();
session_destroy();
header("Location: ../inloggen/index.php?error=logoutSuccessful");
exit;

