<?php
// Placeholder for logout functionality
session_start();
session_destroy();  // End the session

// Redirect to login page or display a message
header("Location: login.php");
exit();
?>