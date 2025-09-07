<?php
session_start();
require_once '../config.php';

// Set admin session for testing
$_SESSION['userid'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['userfullname'] = 'Administrator';
$_SESSION['user_group'] = 'admin';

// Redirect to admin dashboard
header('Location: index.php');
exit;
?>
