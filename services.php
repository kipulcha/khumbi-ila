<?php
require_once 'config.php';
$id = $mydb->select_field("id", "tbl_services", "slug='" . $_GET['slug'] . "'");
$slug = $_GET['slug'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $mydb->select_field("title", "tbl_services", "id='" . $id . "'"); ?> - Nepal Adventures</title>

    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../img/icon.png">
    <meta name="description"
          content="<?php echo strip_tags($mydb->select_field("content", "tbl_services", "id='" . $id . "'")); ?>">
    <meta name="author" content="Rajen Kaji Shrestha">
    <meta name="robots" content="index,follow"/>
    <meta property="og:title" content="<?php echo $mydb->select_field("title", "tbl_services", "id='" . $id . "'"); ?> - Khumbila Adventure Travel"/>
    <meta property="og:image" content="http://www.khumbila-tours.com/admin/site_images/services/<?php echo $mydb->select_field("image", "tbl_services", "id='" . $id . "'"); ?>"/>
    <meta property="og:description"
          content="<?php echo strip_tags($mydb->select_field("content", "tbl_services", "id='" . $id . "'")); ?>"/>

    <!--mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- main stylesheet -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- Sweet Alert -->
    <link href="../css/sweetalert.css" rel="stylesheet" type="text/css" media="all"/>
    <script src="../js/sweetalert.min.js"></script>
    <!-- Sweet Alert link ends -->


    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="../js/respond.js"></script>
    <![endif]-->
</head>

<body>

<!-- Menubar -->
<header class="stricky">
    <div class="strickycontainer">
        <nav class="mainmenu-holder">
            <div class="col-md-2 col-xs-7 col-sm-7 logo">
                <a href="../index.php">
                    <img src="../img/logo.png" class="img-responsive" alt="Khumbila Adventures Travel- Travel Nepal">
                </a>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-10">
                <div class="nav-header">
                    <ul class="navigation list-inline">
                        <li class="dropdown">
                            <a href="#">Tours</a>
                            <ul class="submenu text-left">
                                <?php
                                $mydb->orderByCols = array("created_at");
                                $mydb->where("type", "Tours", "=");
                                $mydb->where("parent_id", 0, "=");
                                $mydb->where("display", "1", "=");
                                $result = $mydb->select("tbl_programs");
                                foreach ($result as $row):
                                    ?>
                                    <li><a href="<?php echo($row['child'] == '0' ? './programs/'.$row['slug']:'#');?>" <?php echo($row['child'] == '1' ? 'class="mega-title"':''); ?>><?=$row['title'];?></a>
                                        <?php if($row['child'] == '1'): ?>
                                            <ul class="submenu">
                                                <?php
                                                $mydb->where("parent_id", $row['id']);
                                                $mydb->where("display", "1", "=");
                                                $subresult = $mydb->select("tbl_programs");
                                                foreach ($subresult as $subrow):
                                                    ?>
                                                    <li><a href="../programs.php?slug=<?=$subrow['slug'];?>"><?=$subrow['title'];?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><a href="../tailor-made.php">Tailor Made</a></li>
                        <li class="dropdown active">
                            <a href="#">Travel Info</a>
                            <ul class="submenu text-left">
                                <?php
                                $mydb->orderByCols = array("created_at");
                                $mydb->where("display", "1", "=");
                                $result = $mydb->select("tbl_services");
                                if($mydb->totalRows > 0):
                                    foreach ($result as $row):
                                        ?>
                                        <li><a href="../services.php?slug=<?=$row['slug'];?>"><?=$row['title'];?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li>
                            <a href="../about.php">About</a>
                        </li>
                        <!--<li><a href="contact">Contact Us</a></li>-->
                        <li><a href="../enquire.php"><span class="inquire">Enquire</span></a></li>
                    </ul>
                </div>

                <div class="nav-footer search">
                    <ul class="list-inline">
                        <li class="hidden-md hidden-lg hidden-sm">
                            <a href="#"><i class="icon icon-Search"></i></a>
                            <ul class="search-box">
                                <li>
                                    <form action="../search" method="post">
                                        <input type="text" name="search" placeholder="Type and Hit Enter">
                                        <button type="submit"><i class="icon icon-Search"></i></button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li class="hidden-xs"><a href="#search"><i class="icon icon-Search"></i></a></li>
                        <li class="menu-expander hidden-lg hidden-md"><a href="#"><i class="icon icon-List"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="search">
        <button type="button" class="close">×</button>
        <form action="../search" method="post">
            <input type="search" name="search" placeholder="type keyword(s) here" />
            <button type="submit" class="btn btn-primary">Search <i class="icon icon-Search"></i></button>
        </form>
    </div>
</header>
<!-- /Menubar -->

<div class="clearfix"></div>

<!-- Service Mail -->
<?php

if (@isset($_POST['contact-service'])) {
    if ($_POST['name'] || $_POST['email'] != "") {

        $from = $_POST['email'];
        $mail_title = 'Khumbila Adventure Travel : ' . $mydb->select_field("title", "tbl_services", "id='" . $id . "'") . '';
        $date = date('F d,Y');
        $to = $mydb->select_field("email", "tbl_contact");
        $message1 =
            '<p style="padding:0 20px;">Khumbila Adventure Travel,</p>
       <p style="padding:0 20px;">This is Service Request from ' . $_POST['name'] . ', Contact : ' . $_POST['contact'] . ', Country : ' . $_POST['country'] . ',</p>
       <p style="padding:0 20px;">' . $_POST['message'] . '</p>
       <p style="padding:0 20px;">Regards,</p>
       <p style="padding:0 20px;">' . $_POST['name'] . '</p>';

        $headers = "From: " . $from . " \r\n";
        $headers .= "Reply-To: noreply@khumbila.com\r\n";
        $headers .= "Return-Path: noreply@khumbila.com\r\n";
        $headers .= "CC:noreply@khumbila.com\r\n";
        $headers .= "BCC: noreply@khumbila.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if (mail($to, $mail_title, $message1, $headers)) {
            $mydb->redirect("../services/$slug&msg=sent");

        } else {
            $mydb->redirect("../services/$slug&msg=fail");

        }
    } else {
        $mydb->redirect("../services/$slug&msg=error");
    }
}

if (@$_GET['msg'] == "sent") {
    echo "<script type='text/javascript'>swal('Thankyou!', 'We will get back to via email within one business day!', 'success')</script>";
} elseif (@$_GET['msg'] == "fail") {
    echo "<script type='text/javascript'>sweetAlert('Please Try Again','something went wrong!','error');</script>";
} elseif (@$_GET['msg'] == "error") {
    echo "<script type='text/javascript'>sweetAlert('Oops','Something Went Wrong!','error');</script>";
}

?>
<!-- //service mail -->

<!-- Home Content -->
<section class="welcome-section">
    <div class="container">
        <div class="latest-news contact-page contact-page-two blog-2-col blog-3-col blog-page">
            <div class="row">

                <div class="col-md-12 pull-left">

                    <p>&nbsp;</p>

                    <p class="text-center"><?php echo $mydb->select_field("content", "tbl_services", "id='" . $id . "'"); ?></p>

                    <p>&nbsp;</p>

                </div>

            </div>
        </div>
    </div>
</section>

<div class="clearfix"></div>
<!-- /Home Content -->

<!-- Contact Footer Body --->
<section class="contact-page contact-page-two blog-2-col blog-3-col">
    <div class="container">
        <div class="row">
            <div class="col-md-6" style="margin-top: 2em;">
                <div class="sidebar-widget-wrapper">
                    <div class="single-sidebar-widget contact-widget">
                        <div class="title">
                            <h2><span><?php echo $mydb->select_field("title", "tbl_contact", "id='1'"); ?></span></h2>
                        </div>
                        <ul class="contact-infos">
                            <li>
                                <div class="icon-box">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="info-text">
                                    <p>KHUMBILA ADVENTURE TRAVEL</br>
                                        <?php echo $mydb->select_field("address", "tbl_contact", "id='1'"); ?></br>
                                        PO BOX : 731
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="icon-box">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="info-text">
                                    <p><?php echo $mydb->select_field("phone", "tbl_contact", "id='1'"); ?></p>
                                </div>
                            </li>
                            <li>
                                <div class="icon-box">
                                    <i class="fa fa-mobile-phone"></i>
                                </div>
                                <div class="info-text">
                                    <p><?php echo $mydb->select_field("mobile", "tbl_contact", "id='1'"); ?></p>
                                </div>
                            </li>
                            <li>
                                <div class="icon-box">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="info-text">
                                    <p>
                                        <a href="mailto:<?php echo $mydb->select_field("phone", "tbl_contact", "id='1'"); ?>">
                                            <?php echo $mydb->select_field("email", "tbl_contact", "id='1'"); ?>
                                        </a>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6" style="margin-top: 2em;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1765.9979654492918!2d85.34611304901675!3d27.717411911498168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb197acf9ab2a9%3A0xec2d287b53903953!2sChabahil!5e0!3m2!1sen!2snp!4v1482664449586" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>

        </div>
    </div>
</section>
<!-- /Contact Footer Body --->

<!-- Newsletter -->
<section class="newsletter">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h3>sign up to our newsletter</h3>
            </div>
            <div class="col-md-7">
                <form method="POST" action="">
                    <input type="email" name="email" placeholder="enter your email address">
                    <button type="submit" name="subscribe">Sign Up Now</button>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- /Newsletter -->

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-widget about-widget">
                    <a href="./img/resources/Brochures.pdf" target="_blank">
                        <div class="title">
                            <h2 class="text-center">request <span>Brochures</span></h2>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-widget post-widget">
                    <a href="http://khumbilafoundation.org" target="_blank">
                        <div class="title">
                            <h2 class="text-center">KHUMBILA <span>FOUNDATION</span></h2>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-widget post-widget">
                    <a href="http://www.lavillasherpani.com/" target="_blank">
                        <div class="title">
                            <h2 class="text-center">LA VILLA <span>SHERPANI</span></h2>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-widget contact-widget">
                    <a href="../contact">
                        <div class="title">
                            <h2 class="text-center">contact<span> info</span></h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<section class="bottom-bar">
    <div class="container">
        <div class="text col-md-4 text-left col-xs-6">
            <p>&copy;2016. Khumbila Adventure Travel. All Rights Reserved. </p>
        </div>
        <div class="social col-md-4 text-center col-xs-6">
            <ul class="list-inline">
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
            </ul>
        </div>
        <div class="text col-md-4 text-right">
          <!--  <p>Coded with <i class="fa fa-heart" style="color: #e33a0c;"></i> by <a href="http://www.ktmrush.com"
                                                                                    target="_blank">KTM Rush</a> / <a
                    href="http://www.rajenshrestha.com.np" target="_blank">Rajen</a>.</p> -->
        </div>
    </div>
</section>
<!-- Footer -->

<!-- Modal -->
<div class="modal contact-page fade booking-form" id="booking-form" tabindex="-1" role="dialog"
     aria-labelledby="booking-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3>Send message for Booking: </h3>
                <form class="contact-form search-form-box" action="inc/booking-form.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Name <span>*</span></label>
                                <input type="text" name="name" placeholder="Enter your name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Email <span>*</span></label>
                                <input type="text" name="email" placeholder="Enter your email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Phone <span>*</span></label>
                                <input type="text" name="phone" placeholder="Enter your phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Try Trip: <span>*</span></label>
                                <select name="trip" class="select-input">
                                    <option value="#">Per Hour</option>
                                    <option value="#">Per Day</option>
                                    <option value="#">Airport Transfer</option>
                                </select>43
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Number of Passenger <span>*</span></label>
                                <input type="text" name="passenger" placeholder="xxxxxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Pickup Date: <span>*</span></label>
                                <input type="text" name="date" placeholder="MM/DD/YYYY" class="date-picker">
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="col-md-12">

                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Pickup Location <span>*</span></label>
                                <textarea name="pickup" placeholder="Enter Your message"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Destination <span>*</span></label>
                                <textarea name="destination" placeholder="Enter Your message"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 clearfix">
                            <label>Additional Note <span>*</span></label>
                            <textarea name="message" placeholder="Enter Your message"></textarea>
                            <button type="submit" class="pull-right thm-btn hvr-sweep-to-top">SEND MESSAGE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/jquery/jquery-1.11.3.min.js"></script>
<script src="../assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../assets/validate.js"></script>

<script src="../assets/owl.carousel-2/owl.carousel.min.js"></script>

<!-- Revolution slider JS -->
<script src="../assets/revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="../assets/revolution/js/jquery.themepunch.revolution.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="../assets/revolution/js/extensions/revolution.extension.video.min.js"></script>

<!-- jQuery ui js -->
<script src="../assets/jquery-ui-1.11.4/jquery-ui.js"></script>

<!-- mixit up -->
<script src="../assets/jquery.mixitup.min.js"></script>

<!-- fancy box -->
<script src="../assets/fancyapps-fancyBox/source/jquery.fancybox.pack.js"></script>

<!-- custom.js -->
<script src="../js/map-script.js"></script>
<script src="../js/default-map-script.js"></script>
<script src="../js/custom.js"></script>

</body>
</html>
