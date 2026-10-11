<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];


// Destroy the session
session_destroy();

// Redirect to the index page
header("Location: index.php");
exit(); // Exit to ensure no further code is executed
?>
