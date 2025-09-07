<?php
/**
 * Debug forgot password functionality
 */

require_once 'config.php';

$email_to = 'jebishnu@gmail.com';

echo "Testing forgot password for: $email_to\n\n";

// Test 1: Direct SQLite query
echo "1. Direct SQLite query:\n";
$pdo = new PDO('sqlite:db_khumbila.sqlite');
$stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE email = ?");
$stmt->execute([$email_to]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Found " . count($result) . " users\n";
foreach ($result as $user) {
    echo "- ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}, Name: {$user['name']}\n";
}

echo "\n2. Using PDOModel:\n";
$mydb->where("email", $email_to, "=");
$result = $mydb->select("tbl_admin");
echo "Total rows: " . $mydb->totalRows . "\n";
echo "Result count: " . count($result) . "\n";

if ($mydb->totalRows > 0) {
    foreach ($result as $user) {
        echo "- ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}, Name: {$user['name']}\n";
    }
} else {
    echo "No users found with PDOModel\n";
}

echo "\n3. Testing with different email:\n";
$test_email = 'info@khumbila.com';
$mydb2 = new PDOModel();
$mydb2->connect("sqlite", "", "", "db_khumbila.sqlite");
$mydb2->where("email", $test_email, "=");
$result2 = $mydb2->select("tbl_admin");
echo "Testing with $test_email - Total rows: " . $mydb2->totalRows . "\n";
?>
