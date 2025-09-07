<!DOCTYPE html>
<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Khumbila Adventure Travel - Nepal Adventures</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="./img/icon.png">
    <meta name="description"
          content="<?php echo strip_tags($mydb->select_field("short_content", "tbl_about", "id='1'")); ?>">

    <!--mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- main stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Sweet Alert -->
    <link href="css/sweetalert.css" rel="stylesheet" type="text/css" media="all"/>
    <script src="js/sweetalert.min.js"></script>
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
                <a href="index.php">
                    <img src="img/logo.png" class="img-responsive" alt="Khumbila Adventures Travel- Travel Nepal">
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
                                    <li><a href="<?php echo($row['child'] == '0' ? './programs.php?slug='.$row['slug']:'#');?>" <?php echo($row['child'] == '1' ? 'class="mega-title"':''); ?>><?=$row['title'];?></a>
                                        <?php if($row['child'] == '1'): ?>
                                            <ul class="submenu">
                                                <?php
                                                $mydb->where("parent_id", $row['id']);
                                                $mydb->where("display", "1", "=");
                                                $subresult = $mydb->select("tbl_programs");
                                                foreach ($subresult as $subrow):
                                                    ?>
                                                    <li><a href="./programs.php?slug=<?=$subrow['slug'];?>"><?=$subrow['title'];?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><a href="./tailor-made.php">Tailor Made</a></li>
                        <li class="dropdown">
                            <a href="#">Travel Info</a>
                            <ul class="submenu text-left">
                                <?php
                                $mydb->orderByCols = array("created_at");
                                $mydb->where("display", "1", "=");
                                $result = $mydb->select("tbl_services");
                                if($mydb->totalRows > 0):
                                    foreach ($result as $row):
                                        ?>
                                        <li><a href="./services.php?slug=<?=$row['slug'];?>"><?=$row['title'];?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li>
                            <a href="./about.php">About</a>
                        </li>
                      <!--  <li><a href="contact">Contact Us</a></li>-->
                        <li class="active"><a href="enquire.php"><span class="inquire">Enquire</span></a></li>
                    </ul>
                </div>

                <div class="nav-footer search">
                    <ul class="list-inline">
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
        <form action="./search" method="post">
            <input type="search" name="search" placeholder="type keyword(s) here" />
            <button type="submit" class="btn btn-primary">Search <i class="icon icon-Search"></i></button>
        </form>
    </div>
</header>
<!-- /Menubar -->

<?php
if (@isset($_POST['enquire'])) {
    $secret = '6Ld4xA8UAAAAAPaRJl6DMPxHdZGl0gIEdHWIX84u';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
    $result = json_decode($url, TRUE);
    if ($result ['success']== 1) {
    if ($_POST['name'] || $_POST['email'] != "") {
        $insertData = array("name" => $_POST['name'], "email" => $_POST['email'], "contact" => $_POST['contact'], "country" => $_POST['country'], "details" => $_POST['details'], "departure_date" => $_POST['departure_date'], "duration" => $_POST['duration'], "adult_guests" => $_POST['adult_guests'], "children_guests" => $_POST['children_guests'], "accommodation" => $_POST['accommodation'], "comments" => $_POST['comments'], "created_at" => date('Y-m-d H:i:s'));
        $insert = $mydb->insert("tbl_enquire", $insertData);
        if (@$insert) {
            $from = $_POST['email'];
            $mail_title = 'Khumbila Adventure Travel : Enquire Mail';
            $date = date('F d,Y');
            $to = $mydb->select_field("email", "tbl_contact");
            $message1 =
                '<p style="padding:0 20px;">Khumbila Adventure Travel,</p>
			<p style="padding:0 20px;">This is enquire messege from ' . $_POST['name'] . ', Contact : ' . $_POST['contact'] . ', Country : ' . $_POST['country'] . ',</p>
			<p style="padding:0 20px;">Details: ' . $_POST['details'] . '</p>
			<p style="padding:0 20px;">Departure Date: ' . $_POST['departure_date'] . '</p>
			<p style="padding:0 20px;">Trip Duration: ' . $_POST['duration'] . '</p>
			<p style="padding:0 20px;">No. of Adult Guests: ' . $_POST['adult_guests'] . '</p>
			<p style="padding:0 20px;">No. of Children Guests: ' . $_POST['children_guests'] . '</p>
			<p style="padding:0 20px;">Level Of Accommodation: ' . $_POST['accommodation'] . '</p>
			<p style="padding:0 20px;">Additional Comments: ' . $_POST['comments'] . '</p>
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
                $mydb->redirect("enquire?msg=sent");

            } else {
                $mydb->redirect("./enquire?msg=fail");

            }
        } else {
            $mydb->redirect("./enquire?msg=fail");
        }
    } else {
        $mydb->redirect("./enquire?msg=error");

    }
    }else{
    $mydb->redirect("./enquire?msg=error");
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

<!-- Page Body --->
<section class="contact-page section-padding contact-page-two blog-2-col blog-3-col">
    <div class="container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-md-12">
                <!--<ul style="padding-left: 0;">
                    <li><p><span class="blue bold">WHATS ON YOUR MIND </span>– Tell us what your travel interests are, and our travel consultant will
                            get back at you with advices and recommendation.</p></li>
                    <li><p><span class="blue bold">CUSTOMIZED ITENARY</span>- Depending on your travel interests and places you would love to see and
                            do, we will consult with you and design the perfect itinerary.</p></li>
                    <li><p><span class="blue bold">PRE-TRIP INFORMATION</span>– Once the travel itinerary is planned, we will provide you information
                            about everything you need to know about your journey.</p></li>
                    <li><p><span class="blue bold">KHUMBILA SUPPORT</span>– From airport transfers to 24/7 phone call support, Khumbila aims to
                            deliver one of a kind service for all our valued customers.</p></li>
                </ul>-->
                <?php echo $mydb->select_field("short_content", "tbl_about", "id='4'"); ?>


                <!--<div class="section-title text-center">
                    <h3 class="golden-text section-padding pb0">TAILOR MADE HOLIDAYS</h3>
                </div>-->
                <p>&nbsp;</p>
                <p class="text-justify">Fill in the form to submit your request for your tailor made holidays. We will get back to via email
                    within one business day. If you want to talk to us directly please contact at <span class="blue bold"><?php echo $mydb->select_field("phone", "tbl_contact", "id='1'"); ?></span>, Mobile <span class="blue bold"><?php echo $mydb->select_field("mobile", "tbl_contact", "id='1'"); ?></span></p>


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

                        <p class="text-justify">Tell us where you would like to visit and specific places you would want to see. Browse
                            through our tours and trip extension programs and tell us what’s on your mind. Let us know
                            If you are planning a trip for a special occasion or events so that we may plan
                            accordingly.</p>

                        <div class="col-md-12 clearfix">
                            <label>Details <span>*</span></label>
                            <textarea name="details" placeholder="Enter Your Details"></textarea>
                        </div>

                        <div style="padding-top:2em" class="col-md-6">
                            <div class="form-grp">
                                <label>Departure Date <span>*</span></label>
                                <input type="date" class="datepicker" placeholder="MM/DD/YYYY" name="departure_date"
                                       placeholder="">
                            </div>
                        </div>

                        <div style="padding-top:2em" class="col-md-6">
                            <div class="form-grp">
                                <label>Trip Duration <span>*</span></label>
                                <input type="text" name="duration" placeholder="Enter your Trip Duration">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>No. of Adult guests <span>*</span></label>
                                <input type="text" name="adult_guests" placeholder="Enter No. of Adult guests">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>No. of Children guests <span>*</span></label>
                                <input type="text" name="children_guests" placeholder="Enter No. of Children guests">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-grp">
                                <label>Level of Accommodation <span>*</span></label>
                                <select class="form-control" name="accommodation">
                                    <option selected disabled>--Select--</option>
                                    <option value="Budget">Budget</option>
                                    <option value="Standard">Standard</option>
                                    <option value="Luxury">Luxury</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 clearfix">
                            <label>Additional Comments/ Questions : <span>*</span></label>
                            <textarea name="comments" placeholder="Enter Additional Comments/ Questions :"></textarea>
                            <div class="g-recaptcha" data-sitekey="6Ld4xA8UAAAAAM6hWB00Aktj1BPVlCEiRNV7j31d"></div>
                            <button name="enquire" onClick="loading()" class="pull-right thm-btn hvr-sweep-to-top">SEND
                                MESSAGE <span id="loader"><i class="fa fa-hourglass-half fa-spin fa-3x fa-fw"
                                                             aria-hidden="true"></i>
							</span></button>
                        </div>

                    </div>
                </form>
            </div>
            <!-- Main Content -->

            <!-- Sidebar -->
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
            <!-- /Sidebar -->

            <div class="col-md-6" style="margin-top: 2em;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1765.9979654492918!2d85.34611304901675!3d27.717411911498168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb197acf9ab2a9%3A0xec2d287b53903953!2sChabahil!5e0!3m2!1sen!2snp!4v1482664449586" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>

        </div>
    </div>
</section>

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
<?php require_once 'footer.php'; ?>
<!-- Footer -->

<!-- Scripts -->
<script src="assets/jquery/jquery-1.11.3.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

<script src="assets/validate.js"></script>

<!-- Revolution slider JS -->
<script src="assets/revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="assets/revolution/js/jquery.themepunch.revolution.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="assets/revolution/js/extensions/revolution.extension.video.min.js"></script>

<script src="assets/owl.carousel-2/owl.carousel.min.js"></script>

<!-- jQuery ui js -->
<script src="assets/jquery-ui-1.11.4/jquery-ui.js"></script>


<!-- mixit up -->
<script src="assets/jquery.mixitup.min.js"></script>
<!-- fancy box -->
<script src="assets/fancyapps-fancyBox/source/jquery.fancybox.pack.js"></script>


<!-- custom.js -->

<script src="js/map-script.js"></script>
<script src="js/default-map-script.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
    $('.datepicker').datepicker({
        startDate: '-3d'
    });
    $('#loader').hide();
    function loading() {
        $('#loader').show();
    }
</script>
</body>
</html>