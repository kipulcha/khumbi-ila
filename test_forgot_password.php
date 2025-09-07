<?php
/**
 * Test forgot password functionality
 */

session_start();
require_once 'config.php';

$email_to = 'jebishnu@gmail.com';

echo "Testing forgot password for: $email_to\n\n";

// Test the exact same code as in forgot_password.php
$mydb->where("email", $email_to, "=");
$result = $mydb->select("tbl_admin");
$count = 1;

echo "Total rows: " . $mydb->totalRows . "\n";
echo "Result count: " . count($result) . "\n";

if ($mydb->totalRows > 0) {
    echo "SUCCESS: User found!\n";
    foreach ($result as $rows) {
        echo "- Username: {$rows['username']}, Email: {$rows['email']}, Name: {$rows['name']}\n";
    }
} else {
    echo "ERROR: No user found!\n";
}

// Test with a different approach
echo "\nTesting with direct query:\n";
$pdo = new PDO('sqlite:db_khumbila.sqlite');
$stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE email = ?");
$stmt->execute([$email_to]);
$directResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Direct query found: " . count($directResult) . " users\n";
?>
