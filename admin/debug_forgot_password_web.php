<?php
session_start();
include('../config.php');

//Send Mail
if (isset($_POST['submit'])) {
    $email_to = $_POST['email_to'];
    
    echo "<h2>Debugging Forgot Password for: $email_to</h2>";
    
    $mydb->where("email", $email_to, "=");
    $result = $mydb->select("tbl_admin");
    $count = 1;

    echo "<p>Total rows: " . $mydb->totalRows . "</p>";
    echo "<p>Result count: " . count($result) . "</p>";

    if ($mydb->totalRows > 0) {
        echo "<div style='color: green;'>SUCCESS: User found!</div>";
        
        foreach ($result as $rows) {
            $your_password = $rows['password'];
            $username = $rows['username'];
            $fullname = $rows['name'];
            $to = $email_to;
            $subject = "Your password for " . C_NAME;

            echo "<p>Username: $username</p>";
            echo "<p>Full name: $fullname</p>";
            echo "<p>Email: $to</p>";
            echo "<p>Subject: $subject</p>";
            
            // Test mail function
            $from = 'rajen@ktmrush.com';
            $headers = "From: $from \r\n";
            $headers .= "Reply-To: noreply@rajenshrestha.com.np\r\n";
            $headers .= "Return-Path: noreply@rajenshrestha.com.np\r\n";
            $headers .= "CC:noreply@rajenshrestha.com.np\r\n";
            $headers .= "BCC: noreply@rajenshrestha.com.np\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $messages = '<div style="font-family: Arial, Helvetica, sans-serif;">
                        <p>Hi ' . $fullname . ',</p>
                        <p>Here are your login credentials:</p>
                        <p><b>Username : ' . $username . '</b><br /><b>Password : ' . $your_password . '</b></p>
                        <p>Please donot share the password or username with anyone for security purposes.</p>
                        <p>&nbsp;</p>
                        <p>
                        Regards,<br>
                        <a href="http://rajenshrestha.com.np" target="_blank">Rajen Kaji Shrestha</a><br>
                        Creative Director<br>
                        KTM Rush Pvt. Ltd.
                        </p>
                        </div>';

            echo "<h3>Email Content:</h3>";
            echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
            echo $messages;
            echo "</div>";
            
            // Test mail function (commented out for testing)
            // $sentMail = mail($to, $subject, $messages, $headers);
            $sentMail = true; // Simulate successful mail for testing
            
            if ($sentMail) {
                echo "<div style='color: green; font-weight: bold;'>SUCCESS: Password would be sent to $to</div>";
                $_SESSION['msg'] = "<div class='alert alert-success'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <h4>Success</h4>
                Your password has been sent to your email address.</div>";
            } else {
                echo "<div style='color: red; font-weight: bold;'>ERROR: Failed to send email</div>";
            }
        }
    } else {
        echo "<div style='color: red;'>ERROR: No user found with email $email_to</div>";
        $_SESSION['msg'] = "<div class='alert alert-error'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <h4>Oopsss..</h4>
        Email doesnot belong to any user.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= C_NAME; ?> | Debug Forgot Password</title>
    <link rel="shortcut icon" href="../images/icon.png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/plugins/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
</head>
<body class="hold-transition lockscreen">
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="./"><?= C_NAME; ?></a>
    </div>
    <div class="lockscreen-name">Debug Forgot Password</div>
    <?=@$_SESSION['msg']; ?>
    
    <div class="lockscreen-item">
        <form class="lockscreen-credentials" method="post">
            <div class="input-group">
                <input type="email" name="email_to" class="form-control" style="padding:0;"
                       placeholder="Please enter your useremail" value="jebishnu@gmail.com" required/>
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="help-block text-center">
        Enter your email to retrieve your password
    </div>
    <div class="text-center">
        <a href="./">Or sign in as a different user</a>
    </div>
</div>
</body>
</html>
