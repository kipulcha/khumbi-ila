<?php
session_start();
include('../config.php');

echo "<h2>Session Test</h2>";
echo "<p>Session ID: " . session_id() . "</p>";

if (isset($_POST['submit'])) {
    $email_to = $_POST['email_to'];
    
    $mydb->where("email", $email_to, "=");
    $result = $mydb->select("tbl_admin");
    
    if ($mydb->totalRows > 0) {
        $_SESSION['msg'] = "<div class='alert alert-success'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <h4>Success</h4>
        Your password has been sent to your email address.</div>";
        echo "<p style='color: green;'>SUCCESS: User found and session message set!</p>";
    } else {
        $_SESSION['msg'] = "<div class='alert alert-error'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <h4>Oopsss..</h4>
        Email doesnot belong to any user.</div>";
        echo "<p style='color: red;'>ERROR: No user found!</p>";
    }
}

echo "<p>Current session message: " . ($_SESSION['msg'] ?? 'NO MESSAGE') . "</p>";
?>

<form method="post">
    <input type="email" name="email_to" value="jebishnu.com" placeholder="Email">
    <button type="submit" name="submit">Test</button>
</form>

<p><a href="forgot_password.php">Go to Forgot Password Page</a></p>
