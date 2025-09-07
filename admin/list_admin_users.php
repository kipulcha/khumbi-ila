<?php
session_start();
include('../config.php');

echo "<h2>Admin Users in Database</h2>";
echo "<p>Database: Remote MySQL (208.91.199.11)</p>";

// Query all admin users
$result = $mydb->select("tbl_admin");

if ($mydb->totalRows > 0) {
    echo "<p><strong>Total Admin Users:</strong> " . $mydb->totalRows . "</p>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th>ID</th><th>Username</th><th>Email</th><th>Name</th><th>User Group</th>";
    echo "</tr>";
    
    foreach ($result as $user) {
        echo "<tr>";
        echo "<td>" . ($user['id'] ?? 'N/A') . "</td>";
        echo "<td>" . ($user['username'] ?? 'N/A') . "</td>";
        echo "<td><strong>" . ($user['email'] ?? 'N/A') . "</strong></td>";
        echo "<td>" . ($user['name'] ?? 'N/A') . "</td>";
        echo "<td>" . ($user['user_group'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>📧 Emails for Admin Login:</h3>";
    echo "<ul>";
    foreach ($result as $user) {
        if (!empty($user['email'])) {
            echo "<li><strong>" . $user['email'] . "</strong> (Username: " . ($user['username'] ?? 'N/A') . ")</li>";
        }
    }
    echo "</ul>";
    
} else {
    echo "<p style='color: red;'>No admin users found in database.</p>";
}

echo "<p><a href='index.php'>← Back to Admin Panel</a></p>";
?>
