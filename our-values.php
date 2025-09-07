<!DOCTYPE html>
<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Values - Khumbila Adventure Travel</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="./img/icon.png">
    <meta name="description"
          content="<?php echo strip_tags($mydb->select_field("short_content", "tbl_about", "id='2'")); ?>">
    <meta name="author" content="Rajen Kaji Shrestha">
    <meta name="robots" content="index,follow"/>
    <meta property="og:title" content="Our Values - Khumbila Adventure Travel"/>
    <meta property="og:image" content="http://www.khumbila-tours.com/admin/site_images/sliders/<?php echo $mydb->select_field("image", "tbl_slider", "slide_status='1'"); ?>"/>
    <meta property="og:description"
          content="<?php echo strip_tags($mydb->select_field("short_content", "tbl_about", "id='2'")); ?>"/>

    <!--mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- main stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="js/respond.js"></script>
    <![endif]-->
</head>

<body>
<!-- Header -->
<div class="container">
    <div class="logo text-center col-xs-10 col-xs-push-1 hidden-xs hidden-sm">
        <a href="index.php">
            <img src="img/logo.png" alt="Khumbila Adventures Travel- Travel Nepal">
        </a>
    </div>
</div>
<!-- /Header -->

<!-- Menubar -->
<header class="stricky">
    <div class="strickycontainer" >
        <nav class="mainmenu-holder">
            <div class="hidden-lg hidden-md col-xs-7 col-sm-7" style="padding: 15px 0;">
                <a href="index.php">
                    <img src="img/logo.png" class="img-responsive" alt="Khumbila Adventures Travel- Travel Nepal">
                </a>
            </div>
            <div class="col-xs-5 col-sm-5 col-md-12">
                <div class="nav-header">
                    <ul class="navigation list-inline">
                        <li class="dropdown">
                            <a href="#">Tours</a>
                            <ul class="submenu text-left">
                                <?php
                                $mydb->where("type", "Tours", "=");
                                $mydb->where("parent_id", 0, "=");
                                $mydb->where("display", "1");
                                $result = $mydb->select("tbl_programs");
                                foreach ($result as $row):
                                    ?>
                                    <li><a href="<?php echo($row['child'] == '0' ? './programs/'.$row['slug']:'#');?>" <?php echo($row['child'] == '1' ? 'class="mega-title"':''); ?>><?=$row['title'];?></a>
                                        <?php if($row['child'] == '1'): ?>
                                            <ul class="submenu">
                                                <?php
                                                $mydb->where("parent_id", $row['id']);
                                                $mydb->where("display", "1");
                                                $subresult = $mydb->select("tbl_programs");
                                                foreach ($subresult as $subrow):
                                                    ?>
                                                    <li><a href="./programs/<?=$subrow['slug'];?>"><?=$subrow['title'];?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li><a href="./tailor-made">Tailor Made</a></li>
                        <li class="dropdown">
                            <a href="#">Travel Info</a>
                            <ul class="submenu text-left">
                                <?php
                                $mydb->where("display", "1");
                                $result = $mydb->select("tbl_services");
                                if($mydb->totalRows > 0):
                                    foreach ($result as $row):
                                        ?>
                                        <li><a href="./services/<?=$row['slug'];?>"><?=$row['title'];?></a></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li>
                            <a href="./about">About</a>
                        </li>
                        <li><a href="enquire"><span class="inquire">Enquire</span></a></li>
                    </ul>
                </div>
                <div class="nav-footer search">
                    <ul class="list-inline">
                        <li>
                            <a href="#"><i class="icon icon-Search"></i></a>
                            <ul class="search-box">
                                <li>
                                    <form action="./search" method="post">
                                        <input type="text" name="post" placeholder="Type and Hit Enter">
                                        <button type="submit"><i class="icon icon-Search"></i></button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-expander hidden-lg hidden-md"><a href="#"><i class="icon icon-List"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- /Menubar -->

<div class="clearfix"></div>

<!--About Area -->
<div class="about-area text-center about-area-three">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="about-container section-padding">
                    <div class="section-title title-three">
                        <div class="title-border">
                            <h1 class="golden-text">
                                <?php echo $mydb->select_field("sub_title", "tbl_about", "id='2'"); ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of About Area-->

<!-- Why Us Banner -->
<section class="call-to-action">
    <div class="container">
        <div class="row">
            <div class="icon-box col-md-4 col-sm-3 col-xs-12">
                <div class="box">
                    <h2>32</h2>
                </div>
                <h3>Years in Business</h3>
            </div>
            <div class="icon-box col-md-4 col-sm-3 col-xs-12">
                <div class="box">
                    <h2>16</h2>
                </div>
                <h3>International Agent Partners</h3>
            </div>
            <div class="icon-box col-md-4 col-sm-3 col-xs-12">
                <div class="box">
                    <h2>62</h2>
                </div>
                <h3>Handpicked Destinations</h3>
            </div>
        </div>
    </div>
</section>
<!-- / Why Us Banner -->

<!--About Area -->
<div class="about-area text-center about-area-three">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="about-container section-padding">
                    <div class="about-text home-about-text">
                        <p>&nbsp;</p>
                        <?php echo strip_tags($mydb->select_field("content", "tbl_about", "id='2'")); ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Values -->
<section class="latest-news testimonials blog-2-col blog-3-col">
    <div class="container">
        <div class="section-title text-center">
            <h3 style="margin: 75px 0;" class="blue">
                "<?php echo strip_tags($mydb->select_field("sub_title", "tbl_about", "id='2'")); ?>"</h3>
        </div>
        <div class="row">
            <?php
            $mydb->orderByCols = array("created_at asc");
            $result = $mydb->select("tbl_values");
            $mydb->limit = "0,3";
            foreach ($result as $row):
                ?>
                <a href="./our-values">
                    <div class="col-md-3 col-sm-3 hidden-xs" style="margin: 0 4%">
                        <div class="single-blog-post">
                            <div class="img-box">
                                <img src="./admin/site_images/values/thumbs/thumb_<?= $row['image']; ?>"
                                     class="img-responsive" alt="Khumbila Adventures Travel- Travel Nepal">
                                <div class="overlay clearfix">
                                    <div class="inner-box">
                                        <div class="box">
                                            <h3>
                                                <?php
                                                $word = explode(' ', $row['title']);
                                                echo $word[0] . '<br>' . $word[1];
                                                ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- /Values -->

<div class="clearfix"></div>

<!-- Footer -->
<?php require_once 'footer.php'; ?>
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
                                <label>Vehicle: <span>*</span></label>
                                <select name="vehicle" class="select-input">
                                    <option value="Black Lincoln MKT">Black Lincoln MKT</option>
                                    <option value="Black Lincoln Sedan">Black Lincoln Sedan</option>
                                    <option value="Mercedes Grand Sedan">Mercedes Grand Sedan</option>
                                    <option value="Black Cadillac Sedan">Black Cadillac Sedan</option>
                                    <option value="Cadillac Escalade Limo">Cadillac Escalade Limo</option>
                                    <option value="Lincoln Stretch Limo">Lincoln Stretch Limo</option>
                                    <option value="Hummer Strecth Limo">Hummer Strecth Limo</option>
                                    <option value="Ford Party Bus Limo">Ford Party Bus Limo</option>
                                    <option value="Mercedes Party Limo">Mercedes Party Limo</option>
                                </select>
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
                            <div class="form-grp">
                                <label>Number of Passenger <span>*</span></label>
                                <input type="text" name="passenger" placeholder="xxxxxxx">
                            </div>
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


<script src="assets/jquery/jquery-1.11.3.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

<script src="http://maps.google.com/maps/api/js"></script>
<script src="assets/gmap.js"></script>
<script src="assets/validate.js"></script>

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


</body>
</html>

