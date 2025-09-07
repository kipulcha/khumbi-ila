<?php
session_start();
include('../../config.php');

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $password = $_POST['password'];
    $dbpassword = $_SESSION['pwd'];
    
    if (password_verify($password, $dbpassword)):
        $mydb->redirect("../index.php");
    else:
        $mydb->redirect("./lockscreen.php?msg=error");
    endif;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= C_NAME; ?> | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../dist/plugins/fontawesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">

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
        <a href="../index.php"><?= C_NAME; ?><br><b>Admin Panel</b></a><br>
    </div>

    <?php if (@$_GET['msg'] == 'error') { ?>
        <div class="lockscreen-name">
            <p class="alert alert-danger text-center"><i class="fa fa-bell"></i> Error! Incorrect Password.</p>
        </div>
    <?php } ?>

    <!-- User name -->
    <div class="lockscreen-name"><?php echo $_SESSION['username']; ?></div>

    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
            <img src="../dist/img/admin.jpg" class="img-circle" alt="User Image">
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form method="post" class="lockscreen-credentials">
            <div class="input-group">
                <input type="password" name="password" class="form-control" placeholder="password"/>
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i>
                    </button>
                </div>
            </div>
        </form><!-- /.lockscreen credentials -->

    </div><!-- /.lockscreen-item -->
    <div class="help-block text-center">
        Enter your password to retrieve your session
    </div>
    <div class="text-center">
        <a href="../index.php">Or sign in as a different user</a>
    </div>
    <div class="lockscreen-footer text-center">
        Copyright &copy; <?= date('Y'); ?> <b><a href="http://rajenshrestha.com.np" class="text-black">KTM Rush.</a></b><br>
        All rights reserved
    </div>
</div><!-- /.center -->

<!-- jQuery 2.1.4 -->
<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
