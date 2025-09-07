<?php
session_start();
include('../config.php');

echo "<h2>Reset Password for jebishnu@gmail.com</h2>";

// New password to set
$new_password = "admin123";
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

echo "<p><strong>Setting new password:</strong> " . $new_password . "</p>";
echo "<p><strong>Hashed password:</strong> " . $hashed_password . "</p>";

// Update the password in the database
$mydb->where("email", "jebishnu@gmail.com", "=");
$update_data = array("password" => $hashed_password);
$result = $mydb->update("tbl_admin", $update_data);

if ($result) {
    echo "<div style='color: green; font-weight: bold;'>✅ SUCCESS: Password updated successfully!</div>";
    echo "<p><strong>New Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> jebishnu@gmail.com</li>";
    echo "<li><strong>Username:</strong> admins</li>";
    echo "<li><strong>Password:</strong> " . $new_password . "</li>";
    echo "</ul>";
} else {
    echo "<div style='color: red; font-weight: bold;'>❌ ERROR: Failed to update password</div>";
    echo "<p>Error: " . $mydb->getLastError() . "</p>";
}

echo "<p><a href='index.php'>← Back to Admin Panel</a></p>";
?>
