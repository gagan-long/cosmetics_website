<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or public index
header("Location: login.php"); // Change this if you want to redirect to a different page
exit();
?>
