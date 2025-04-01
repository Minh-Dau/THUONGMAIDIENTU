<?php
    session_start(); // Start the session

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the main page or any other appropriate action
    header("Location: trangchinh.php");
    exit();
?>