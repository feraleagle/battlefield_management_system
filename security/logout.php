<?php
session_start();

// Handle logout

// Destroy the session and all its data
$_SESSION = [];
session_destroy();
header('Location: ../login.php');
exit;
