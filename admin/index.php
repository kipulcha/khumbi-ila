<?php
session_start();
require_once '../config.php';

//Logout
if (@$_GET['u_task'] == 'logout') {
    session_destroy();
    $mydb->redirect("./");
}

//Session Expire
$timeout = 2700; // Number of seconds until it times out. 
if (isset($_SESSION['timeout'])) {
    $duration = time() - (int)$_SESSION['timeout'];
    if ($duration > $timeout) {
        session_destroy();
        $mydb->redirect("./");
        session_start();
    }
}
$_SESSION['timeout'] = time();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <!--meta http-equiv="refresh" content="3"-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= C_NAME; ?> | Dashboard</title>
    <link rel="shortcut icon" href="../img/icon.png">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="dist/plugins/fontawesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/select2.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" type="text/css" href="plugins/datepicker/daterangepicker.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="../js/html5shiv.min.js"></script>
    <script src="../js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.1.4 -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Sweet Alert -->
    <script src="../js/sweetalert-dev.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="../css/sweetalert.css">
</head>

<?php if (!@$_SESSION['userid']) { ?>

    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../index.php"><b><?= C_NAME; ?></b><br>Admin Panel</a><br>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Please Log in</p>
            <div id="errorMsg"></div>
            <form action="" method="post" id="adminlogin">
                <div class="form-group has-feedback">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username"/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <input type="submit" id="submit" class="btn btn-primary btn-block btn-flat" value="Log In"/>
                    </div><!-- /.col -->
                </div>
            </form>
            <a href="forgot_password.php">I forgot my password</a><br>

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

    </script>

    <script type="text/javascript">
        $(document).ready(function () {

            $("#submit").click(function () {
                $("div#errorMsg").css("display", "block");
                $("div#errorMsg").html('<p class="alert alert-info"> <i class="fa fa-refresh faa-spin animated"></i> Please Wait!</p>');

                var txtUserName = $("input#username").val();
                var txtPassword = $("input#password").val();

                if ((txtUserName == "" ) || (txtPassword == "")) {

                    $("div#errorMsg").css("display", "block");
                    $("div#errorMsg").fadeTo(900, 1, function () {
                        $(this).html("<p class='alert alert-danger'>Please Fill Blank Fields</p>");
                    });
                    return false;

                } else {

                    $.post("./lib/login.php", {
                        username: $('#username').val(),
                        password: $('#password').val(),
                        rand: Math.random()
                    }, function (data) {
                        if (data == 'yes') {
                            $("div#errorMsg").fadeTo(900, 1, function () {
                                $(this).html("Logging in.....").addClass('alert alert-success').fadeTo(900, 1, function () {
                                    document.location = 'index.php';
                                });
                            });

                        } else {
                            $("div#errorMsg").fadeTo(900, 1, function () {
                                $(this).html('<p class="alert alert-danger"> Invalid users</p>');
                            });
                        }
                    });
                    return false;
                }
            });
        });
    </script>

    </body>

    <?php
} else {
?>

<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">

<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin </b>Panel</span> </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span>
            </a> <a class="coy_brand" target="_blank" role="button" href="#"> <b><?= C_NAME; ?></b> </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Tasks: style can be found in dropdown.less -->
                    <li class="dropdown tasks-menu"><a href="pages/lockscreen.php" title="Lock Screen"> <i
                                class="fa fa-lock"></i> </a></li>
                    <li class="dropdown tasks-menu"><a href="../index.php" target="_blank" title="View Site"> <i
                                class="fa fa-globe"></i> </a></li>
                    <!-- Control Sidebar Toggle Button -->
                    <li class="tasks-menu"><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                           title="Admin Name"> <i class="fa fa-user"></i> </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header"><img src="dist/img/admin.jpg" class="img-circle" alt="User Image">
                                <p> Admin
                                    <small>Member since Sep. 2015</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left"><a href="./?page=user&id=<?= $_SESSION['userid']; ?>"
                                                          class="btn btn-info btn-flat">Profile</a></div>
                                <div class="pull-right"><a href="?u_task=logout" class="btn btn-default btn-flat">Log
                                        out</a></div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image"><img src="dist/img/admin.jpg" class="img-circle" alt="User Image"></div>
                <div class="pull-left info">
                    <p><?php echo $_SESSION['userfullname']; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a></div>
            </div>
            <!-- search form>
                <form action="#" method="get" class="sidebar-form">
                  <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                      <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </form>
                <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu text-justify">

                <li class="header">MAIN NAVIGATION</li>

                <li class="<?php echo(@$_GET['page'] == '' ? 'active' : ''); ?> treeview"><a href="./"> <i
                            class="fa fa-dashboard"></i> <span>Dashboard</span> </a></li>

                <li class="<?php echo(@$_GET['page'] == 'pages' || @$_GET['page'] == 'pages-main' ? 'active' : ''); ?> treeview">
                    <a href="./?page=pages-main"> <i class="fa fa-copy"></i> <span>Pages</span> <i
                            class="fa fa-angle-left pull-right"></i> </a>
                    <ul class="treeview-menu">
                        <?php
                        $result = $mydb->select("tbl_about");
                        foreach ($result as $row):
                            ?>
                            <li><a href="./?page=pages&id=<?= $row['id']; ?>"><i
                                        class="fa fa-arrow-circle-o-right"></i> <?= $row['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li <?php echo(@$_GET['page'] == 'contact' || @$_GET['page'] == 'contact-add' ? 'class = active' : ''); ?>>
                    <a href="./?page=contact"> <i class="fa fa-map-marker"></i> <span>Contact Details</span></a></li>

                <li <?php echo(@$_GET['page'] == 'slider' || @$_GET['page'] == 'slider-add' ? 'class = "active"' : ''); ?>>
                    <a href="./?page=slider"> <i class="fa fa-picture-o"></i> <span>Sliders</span> </a></li>

                <li <?php echo(@$_GET['page'] == 'team' || @$_GET['page'] == 'team-add' ? 'class = "active"' : ''); ?>>
                    <a href="./?page=team"> <i class="fa fa-users"></i> <span>Team/Members</span> </a></li>

                <li <?php echo(@$_GET['page'] == 'services' || @$_GET['page'] == 'services-add' ? 'class = "active"' : ''); ?>>
                    <a href="./?page=services"> <i class="fa fa-suitcase" style="font-size: 1.3em;"></i>
                        <span>Services</span></a>
                </li>

                <li <?php echo(@$_GET['page'] == 'values' || @$_GET['page'] == 'values-add' ? 'class = "active"' : ''); ?>>
                    <a href="./?page=values"> <i class="fa  fa-lightbulb-o" style="font-size: 1.3em;"></i> <span>Our Values</span></a>
                </li>

                <li <?php echo(@$_GET['page'] == 'programs-headers' ? 'class = "active"' : ''); ?>>
                    <a href="./?page=program-headers"> <i class="fa fa-rebel"></i> <span>Programs</span></a></li>

                <li <?php echo(@$_GET['page'] == 'packages' || @$_GET['page'] == 'packages-add'  ? 'class = "active"' : ''); ?>>
                    <a href="./?page=packages"> <i class="fa fa-cubes"></i> <span>Packages</span></a></li>

                <li <?php echo(@$_GET['page'] == 'subscribers' ? 'class = "active"' : ''); ?>>
                    <a href="./?page=subscribers"> <i class="fa fa-rss"></i> <span>Subscribers</span></a></li>
                <li class="<?php echo(@$_GET['page'] == 'media' || @$_GET['page'] == 'media' ? 'active' : ''); ?>"><a href="./?page=media"><i class="fa fa-picture-o" aria-hidden="true"></i> <span>Media</span></a></li>

            </ul>

        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->

    <?php
    if (@$_GET['page']) {
        if (file_exists("./pages/" . $_GET['page'] . ".php"))
            include("./pages/" . $_GET['page'] . ".php");
        else
            include("./pages/404.php");
    } else {
        include("./pages/home.php");
    }
    ?>

    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs"><a href="http://www.rajenshrestha.com.np" target="_blank"><img
                    src="dist/img/footer-logo.jpg"/></a>&nbsp;&nbsp; <a href="http://www.ktmrush.com"
                                                                        target="_blank"><img
                    src="dist/img/rush-footer.png"/></a></div>
        <strong>&copy; <?= date('Y'); ?>. <?= C_NAME; ?>.</strong> <b>All rights reserved</b>. <b><a
                href="http://www.rajenshrestha.com.np" target="_blank">Rajen Admins (Ver 2.1.0)</a></b>.
    </footer>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-gears"></i>
                    Settings</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Admin Settings</h3>
                <ul class="control-sidebar-menu">
                    <li><a href="./?page=user&id=<?= $_SESSION['userid']; ?>"> <i
                                class="menu-icon fa fa-user bg-green"></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">View Profile</h4>
                            </div>
                        </a></li>
                    <?php
                    echo($_SESSION['user_group'] == 0 ?
                        '<li> <a href="./?page=user-add"> <i class="menu-icon fa fa-user-plus bg-green"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Add User</h4>
            </div>
            </a> </li>
			<li> <a href="./?page=users"> <i class="menu-icon fa fa-users bg-green"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">View Users</h4>
            </div>
            </a> </li>
			' : '');
                    ?>
                </ul>
                <?php
                echo($_SESSION['user_group'] == 0 ? '
        <h3 class="control-sidebar-heading">SEO Settings</h3>
        <ul class="control-sidebar-menu">
          <li> <a href="./?page=seo"> <i class="menu-icon fa fa-tags bg-light-blue"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Edit SEO</h4>
            </div>
            </a> </li>
        </ul>' : '');
                ?>
                <!-- /.control-sidebar-menu -->
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery UI 1.11.4 -->
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript">
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.5 -->
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script type="text/javascript" src="plugins/select2/select2.full.min.js"></script>
<!-- FastClick -->
<script type="text/javascript" src="plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script type="text/javascript" src="dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script type="text/javascript" src="dist/js/pages/dashboard.js"></script>
<!-- Date Picker -->
<script type="text/javascript" src="plugins/datepicker/moment.min.js"></script>
<script type="text/javascript" src="plugins/datepicker/daterangepicker.js"></script>
<!-- DataTables -->
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- CK Editor -->
<script type="text/javascript" src="plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $("#cover").hide();
    $('form').submit(function () {
        $("#cover").show();
    });
</script>
<?php
 }
?>
</body>
</html>
