<?php
session_start();

$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to homePage.html
header("Location: homePage.html");
exit;
?>
