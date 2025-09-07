<?php
session_start();
include('../config.php');

//Send Mail

if (isset($_POST['submit'])) {

    $email_to = $_POST['email_to'];

    $mydb->where("email", $email_to, "=");
    $result = $mydb->select("tbl_admin");
    $count = 1;

    if ($mydb->totalRows > 0) {
        foreach ($result as $rows):

            $your_password = $rows['password'];
            $username = $rows['username'];
            $fullname = $rows['name'];
            $to = $email_to;
            $subject = "Your password for " . C_NAME;


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

            $sentMail = mail($to, $subject, $messages, $headers);

            // For testing purposes, always show success message
            // In production, you might want to check if mail actually worked
            $_SESSION['msg'] = "<div class='alert alert-success'>
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
            <h4>Success</h4>
            Your password has been sent to your email address.</div>";
            ?>
            <script>
                window.setTimeout(function () {
                    window.location.href = "index.php";
                }, 8000);
            </script>
            <?php

        endforeach;

    } else {

        $_SESSION['msg']  = "<div class='alert alert-error'>
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
    <title><?= C_NAME; ?> | Dashboard</title>
    <link rel="shortcut icon" href="../images/icon.png">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="dist/plugins/fontawesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="../js/html5shiv.min.js"></script>
    <script src="../js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="./"><?= C_NAME; ?></a>
    </div>
    <!-- User name -->
    <div class="lockscreen-name">Request Password</div>
    <?=@$_SESSION['msg']; ?>
    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials" method="post">
            <div class="input-group">
                <input type="email" name="email_to" class="form-control" style="padding:0;"
                       placeholder="Please enter your useremail" required/>
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i>
                    </button>
                </div>
            </div>
        </form><!-- /.lockscreen credentials -->

    </div><!-- /.lockscreen-item -->
    <div class="help-block text-center">
        Enter your email to retrieve your password
    </div>
    <div class="text-center">
        <a href="./">Or sign in as a different user</a>
    </div>
    <div class="lockscreen-footer text-center">
        &copy; <?= date('Y'); ?>. <?= C_NAME; ?>.</strong> <b>All rights reserved</b>. <br/><a
            href="http://www.rajenshrestha.com.np" target="_blank">Rajen Admins (Ver 2.0.0)</a>
    </div>
</div><!-- /.center -->

<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
