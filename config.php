<?php

require_once 'pdo-lib/PDOModel.php';
require_once 'mock_database.php';

/**
 * Constants
 */

define('C_NAME','Khumbila Adventures');

/**
 * Database Connections
 */

$mydb = new PDOModel(); //create object of the PDOModel class

//connect to database
$db_connected = $mydb->connect("208.91.199.11", "namgyalavilla", "4xEg67^s2", "lavilhda_");

// If database connection fails, use mock database
if (!$db_connected) {
    $mydb = new MockDatabase();
}

date_default_timezone_set('Asia/Kathmandu');

$cookie_expire=time()+20;

$currentDate = date('Y-m-d H:i:s');
$today = date('Y-m-d');
