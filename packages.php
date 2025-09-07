<?php
require_once 'config.php';
$id = $mydb->select_field("id", "tbl_packages", "slug='" . $_GET['slug'] . "'");
$slug = $_GET['slug'];
$programId = array();
$programId = $mydb->select_field("programs", "tbl_packages", "slug='" . $_GET['slug'] . "'");
/*var_dump(json_decode($programId));*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $mydb->select_field("title", "tbl_packages", "id='" . $id . "'"); ?> - Khumbila Adventures</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../img/icon.png">
    <meta name="description"
          content="<?php echo $mydb->select_field("title", "tbl_packages", "id='" . $id . "'"); ?>">
    <meta name="author" content="Rajen Kaji Shrestha">
    <meta name="robots" content="index,nofollow"/>
    <meta property="og:title"
          content="<?= $mydb->select_field("title", "tbl_packages", "id='" . $id . "'"); ?> - Khumbila Adventures"/>
    <meta property="og:image"
          content="http://www.khumbila-tours.com/admin/site_images/packages/<?= $mydb->select_field("image", "tbl_packages", "id='" . $id . "'"); ?>"/>
    <meta property="og:description"
          content="<?php echo strip_tags($mydb->select_field("overview", "tbl_packages", "id='" . $id . "'")); ?>"/>

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
    <script src="js/respond.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
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
                        <li class="dropdown active">
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
                                    <li>
                                        <a href="<?php echo($row['child'] == '0' ? './programs.php?slug=' . $row['slug'] : '#'); ?>" <?php echo($row['child'] == '1' ? 'class="mega-title"' : ''); ?>><?= $row['title']; ?></a>
                                        <?php if ($row['child'] == '1'): ?>
                                            <ul class="submenu">
                                                <?php
                                                $mydb->where("parent_id", $row['id']);
                                                $mydb->where("display", "1", "=");
                                                $subresult = $mydb->select("tbl_programs");
                                                foreach ($subresult as $subrow):
                                                    ?>
                                                    <li>
                                                        <a href="../programs.php?slug=<?= $subrow['slug']; ?>"><?= $subrow['title']; ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><a href="../tailor-made.php">Tailor Made</a></li>
                        <li class="dropdown">
                            <a href="#">Travel Info</a>
                            <ul class="submenu text-left">
                                <?php
                                $mydb->orderByCols = array("created_at");
                                $mydb->where("display", "1", "=");
                                $result = $mydb->select("tbl_services");
                                if ($mydb->totalRows > 0):
                                    foreach ($result as $row):
                                        ?>
                                        <li><a href="../services.php?slug=<?= $row['slug']; ?>"><?= $row['title']; ?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li>
                            <a href="../about.php">About</a>
                        </li>
                        <!--<li><a href="contact.php">Contact Us</a></li>-->
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
            <input type="search" name="search" placeholder="type keyword(s) here"/>
            <button type="submit" class="btn btn-primary">Search <i class="icon icon-Search"></i></button>
        </form>
    </div>
</header>
<!-- /Menubar -->

<div class="clearfix"></div>

<!-- Package Mail -->
<?php

if (@isset($_POST['contact-package'])) {
    $secret = '6Ld4xA8UAAAAAPaRJl6DMPxHdZGl0gIEdHWIX84u';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
    $result = json_decode($url, TRUE);
    if ($result ['success'] == 1) {
        if ($_POST['name'] || $_POST['email'] != "") {

            $from = $_POST['email'];
            $mail_title = 'Khumbila Adventure Travel : ' . $mydb->select_field("title", "tbl_packages", "id='" . $id . "'") . '';
            $date = date('F d,Y');
            $to = $mydb->select_field("email", "tbl_contact");
            $message1 =
                '<p style="padding:0 20px;">Khumbila Adventure Travel,</p>
       <p style="padding:0 20px;">This is Package Request from ' . $_POST['name'] . ', Contact : ' . $_POST['contact'] . ', Country : ' . $_POST['country'] . ',</p>
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
                $mydb->redirect("../packages/$slug&msg=sent");

            } else {
                $mydb->redirect("../packages/$slug&msg=fail");
            }
        } else {
            $mydb->redirect("../packages/$slug&msg=error");
        }
    } else {
        $mydb->redirect("../packages/$slug&msg=error");
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
<!-- //package mail -->

<!-- Main Content -->
<div class="single-car-content single-blog-post-page section-padding">
    <div class="col-md-12">
        <div class="gallery-widget">
            <ul class="list-inline">
                <?php
                $mydb->where("p_id", $id, "=");
                $mydb->orderByCols= array("id desc");
                $result = $mydb->select("tbl_trip_gallery");

                if ($mydb->totalRows > 0) {
                    foreach ($result as $row):
                        ?>
                        <li class="col-md-3 col-sm-4 col-xs-6">
                            <a class="fancybox" rel="gallery" caption="<?= $row['caption']; ?>" href="../admin/site_images/packages-gallery/<?= $row['filename']; ?>" title="<?= $row['caption'];?>">
                                <img src="../admin/site_images/packages-gallery/thumb_<?= $row['filename']; ?>" title="<?= $row['caption']; ?>"/>
                            </a>
                        </li>
                        <?php
                    endforeach;
                }
                ?>
            </ul>
        </div>
        <!--- /Gallery Insta Look -->

        <div class="clearfix"></div>

        <!-- Package Main -->
        <div class="container">
            <div class="col-md-12 col-xs-12 section-padding">

            <h2>
                <?php
                $price = $mydb->select_field("price", "tbl_packages", "id='" . $id . "'");
                $duration = $mydb->select_field("duration", "tbl_packages", "id='" . $id . "'");
                ?>
                <div class="col-md-9 text-center cap-caption">
                    <?= $mydb->select_field("title", "tbl_packages", "id='" . $id . "'"); ?>
                    <?php echo($duration ? '- ' . $duration . ' days' : ''); ?>
                    <?php echo($price ? '- $' . $price : ''); ?>
                </div>

                <div class="clearfix hidden-lg hidden-sm hidden-md"></div>

                <div class="col-md-3 cap-caption">
                    <small>Share:</small>
                    <a href="http://www.facebook.com/sharer/sharer.php?u=http://khumbila-tours.com/packages/<?= $mydb->select_field("slug", "tbl_packages", "id='" . $id . "'"); ?>"
                       target="_blank"><i class="fa fa-facebook-official" style="color:#3B5998; "></i></</div>
            </h2>
            <div class="clearfix"></div>
            <!--<p style="margin-top: 2em;">
                <a class="bookPackage" href="#" data-toggle="modal" data-target=".booking-form">Book This Trip&nbsp;&nbsp;&nbsp;<i
                        class="fa fa-angle-right" style="color: #1d1d1d;"></i></a>
            </p>-->

            <!--- Tab Details -->
            <div class="single-car-tab-wrapper">
                <div class="tab-title">
                    <ul role="tablist">
                        <li class="active" data-tab-name="prices"><a href="#overview" aria-controls="overview"
                                                                     role="tab"
                                                                     data-toggle="tab" class="cap-ul padding20">Overview</a>
                        </li>
                        <li data-tab-name="review"><a href="#review" aria-controls="review" role="tab"
                                                      data-toggle="tab" class="cap-ul padding20">Itinerary</a>
                        </li>
                        <li data-tab-name="prices"><a href="#prices" aria-controls="prices" role="tab"
                                                      data-toggle="tab" class="cap-ul padding20">PRICE</a></li>
                        <!--<li data-tab-name="additional-info"><a href="#additional-info"
                                                               aria-controls="additional-info"
                                                               role="tab" data-toggle="tab">Informations</a></li>-->
                        <li data-tab-name="additional-info"><a href="#enquire"
                                                               aria-controls="additional-info"
                                                               role="tab" data-toggle="tab" class="cap-ul padding20">Enquire
                                this trip</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="single-tab-content tab-pane fade in active" id="overview">
                        <p class="container"><?= $mydb->select_field("overview", "tbl_packages", "id='" . $id . "'"); ?></p>
                    </div>
                    <div class="single-tab-content tab-pane fade" id="prices">
                        <p class="container"><?= $mydb->select_field("prices", "tbl_packages", "id='" . $id . "'"); ?></p>
                    </div>
                    <div class="single-tab-content tab-pane fade " id="review">
                        <!--Comments Area-->
                        <p class="container"><?= $mydb->select_field("Itinerary", "tbl_packages", "id='" . $id . "'"); ?></p>
                    </div>
                    <!--<div class="single-tab-content tab-pane fade " id="additional-info">
                            <p><? /*= $mydb->select_field("Information", "tbl_packages", "id='" . $id . "'"); */ ?></p>
                        </div>-->
                    <div class="single-tab-content tab-pane contact-page fade " id="enquire">
                        <p class="text-justify">Fill in the form to Enquire for
                            <b><?= $mydb->select_field("title", "tbl_packages", "id='" . $id . "'"); ?></b>. We will get
                            back to via email
                            within one business day. If you want to talk to us directly please contact at <span
                                class="blue bold"><?php echo $mydb->select_field("phone", "tbl_contact", "id='1'"); ?></span>,
                            Mobile <span
                                class="blue bold"><?php echo $mydb->select_field("mobile", "tbl_contact", "id='1'"); ?></span>
                        </p>

                        <p class="text-justify">Office Hrs. <span class="blue bold">Monday to Sunday 10 -5 pm</span>.
                            Saturday - Holiday</p>

                        <form class="contact-form" method="post" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <label>Name <span>*</span></label>
                                        <input type="text" name="name" placeholder="Enter your First Name Last Name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <label>Email <span>*</span></label>
                                        <input type="email" name="email" placeholder="Enter your Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <label>Day Time Phone <span>*</span></label>
                                        <input type="text" name="contact" placeholder="Enter your Phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <label>Country <span>*</span></label>
                                        <input type="text" name="country" placeholder="Enter your Country">
                                    </div>
                                </div>
                                <div class="col-md-12 clearfix">
                                    <label>Questions/ Comments <span>*</span></label>
                                    <textarea name="message" placeholder="Enter Your Questions/ Comments "></textarea>
                                    <div class="g-recaptcha"
                                         data-sitekey="6Ld4xA8UAAAAAM6hWB00Aktj1BPVlCEiRNV7j31d"></div>
                                    <button type="submit" name="contact-package"
                                            class="pull-right thm-btn hvr-sweep-to-top">SEND MESSAGE
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--- Tab Details -->
        </div>
        </div>
        <!-- /Package Main -->
    </div>
</div>
<!-- /Main Content -->

<div class="clearfix"></div>

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
                    <a href="../contact.php">
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
        <!--    <p>&copy;2016. Khumbila Adventure Travel. All Rights Reserved. </p> -->
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
                    href="http://www.rajenshrestha.com.np" target="_blank">Rajen</a>.</p>-->
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
                                </select>
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

