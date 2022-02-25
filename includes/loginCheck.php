<?php
function loginCheck(){
    if (!isset($_SESSION['loggedInUser'])) {
        header("Location: ../inloggen/index.php?error=notLoggedIn");
        exit;
    }
}
function loginCheckPageSpecific($right){
    if ($_SESSION['loggedInUser'][$right] !== "true") {
        header("Location: ../medewerkers/");
        exit;
    }
}