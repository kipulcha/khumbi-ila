<?php
session_start();
require_once '../config.php';

$email_to = 'jebishnu@gmail.com';

echo "<h2>Testing Forgot Password for: $email_to</h2>";

// Test the exact same code as in forgot_password.php
$mydb->where("email", $email_to, "=");
$result = $mydb->select("tbl_admin");

echo "<p>Total rows: " . ($mydb->totalRows ?? 'NULL') . "</p>";
echo "<p>Result count: " . (is_array($result) ? count($result) : 'NOT ARRAY') . "</p>";
echo "<p>Result type: " . gettype($result) . "</p>";
echo "<p>Error: " . (is_array($mydb->error) ? implode(', ', $mydb->error) : ($mydb->error ?? 'NO ERROR')) . "</p>";
echo "<p>SQL: " . ($mydb->sql ?? 'NO SQL') . "</p>";

if ($mydb->totalRows > 0) {
    echo "<div style='color: green; font-weight: bold;'>SUCCESS: User found!</div>";
    foreach ($result as $rows) {
        echo "<p>- Username: {$rows['username']}, Email: {$rows['email']}, Name: {$rows['name']}</p>";
    }
} else {
    echo "<div style='color: red; font-weight: bold;'>ERROR: No user found!</div>";
}

// Test POST simulation
if ($_POST) {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    $email_to = $_POST['email_to'];
    $mydb->where("email", $email_to, "=");
    $result = $mydb->select("tbl_admin");
    
    echo "<p>POST Test - Total rows: " . $mydb->totalRows . "</p>";
    if ($mydb->totalRows > 0) {
        echo "<div style='color: green;'>POST SUCCESS: User found!</div>";
    } else {
        echo "<div style='color: red;'>POST ERROR: No user found!</div>";
    }
}
?>

<form method="post">
    <input type="email" name="email_to" value="jebishnu@gmail.com" placeholder="Email">
    <button type="submit" name="submit">Test Submit</button>
</form>
