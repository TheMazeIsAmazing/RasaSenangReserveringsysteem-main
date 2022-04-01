<?php
session_start();
session_destroy();
header("Location: ../inloggen/index.php?error=logoutSuccessful");
exit;

