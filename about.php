<!DOCTYPE html>
<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Khumbila Adventure Travel</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="./img/icon.png">
    <meta name="description"
          content="<?php echo strip_tags($mydb->select_field("short_content", "tbl_about", "id='1'")); ?>">
    <meta name="author" content="Rajen Kaji Shrestha">
    <meta name="robots" content="index,follow"/>
    <meta property="og:title" content="About Us - Khumbila Adventure Travel"/>
    <meta property="og:image" content="http://www.khumbila-tours.com/admin/site_images/sliders/<?php echo $mydb->select_field("image", "tbl_slider", "slide_status='1'"); ?>"/>
    <meta property="og:description"
          content="<?php echo strip_tags($mydb->select_field("short_content", "tbl_about", "id='1'")); ?>"/>

    <!--mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- main stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Sweet Alert -->
    <link href="css/sweetalert.css" rel="stylesheet" type="text/css" media="all" />
    <script src="js/sweetalert.min.js"></script>
    <!-- Sweet Alert link ends -->

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="js/respond.js"></script>
    <![endif]-->
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
                        <li class="active">
                            <a href="./about.php">About</a>
                        </li>
                      <!--  <li><a href="contact.php">Contact Us</a></li> -->
                        <li><a href="enquire.php"><span class="inquire">Enquire</span></a></li>
                    </ul>
                </div>

                <div class="nav-footer search">
                    <ul class="list-inline">
                        <li><a href="#search"><i class="icon icon-Search"></i></a></li>
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

<div class="clearfix"></div>

<div class="section-padding">
    <!--About Area -->
    <div class="about-area text-center about-area-three">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!--- Gallery Insta Look -->
                    <div class="footer-widget gallery-widget">
                        <ul class="list-inline">
                            <?php
                            $mainFolder    = './admin/site_images/media';
                            $extensions    = array(".jpg",".png",".gif",".JPG",".PNG",".GIF");


                            /* ---------------------------SHOW PHOTOS INSIDE CONTENT GALLERY ALBUMS FOLDER -----------------------------*/
                            $src_folder = $mainFolder;
                            $src_files  = scandir($src_folder);

                            $files = array();
                            foreach ($src_files as $file) {
                                $ext = strrchr($file, '.');
                                if(in_array($ext, $extensions)) {

                                    array_push( $files, $file );

                                    $thumb = $src_folder.'/thumbs/'.$file;
                                    if (!file_exists($thumb)) {
                                        echo "No Thumb Images";

                                    }

                                }
                            }
                            if ( count($files) == 0 ) {
                                echo "No Images";
                            }else {
                                $count = 1;
                                $start = count($files);
                                for ($i = 0; $i < $start; $i++) {
                                    if (isset($files[$i]) && is_file($src_folder . '/' . $files[$i])) {
                                        ?>
                                        <li class="col-md-3 col-sm-4 col-xs-6">
                                            <a class="fancybox"  rel="group"
                                               href="<?= $src_folder; ?>/<?= $files[$i]; ?>">
                                                <img src="<?= $src_folder; ?>/thumbs/<?= $files[$i]; ?>"
                                                     alt="Image"/>
                                            </a>
                                        </li>
                                        <?php
                                        if ($count % 5 == 0) {
                                            echo "<div class='clearfix'></div>";
                                        }
                                        $count++;
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <!--- /Gallery Insta Look -->

                </div>
            </div>
        </div>
    </div>
    <!--End of About Area-->

    <div class="clearfix"></div>

    <!--- Tab Details --->
    <div class="single-car-content single-blog-post-page">
        <div class="container">
            <div class="row">
                <!--- Tab Details --->
                <div class="single-car-tab-wrapper">
                    <div class="tab-title center-block">
                        <ul role="tablist">
                            <li class="active" data-tab-name="prices"><a href="#about" aria-controls="overview" role="tab"
                                                                         data-toggle="tab">About</a></li>
                            <li data-tab-name="prices"><a href="#team" aria-controls="prices" role="tab"
                                                          data-toggle="tab">Our Team</a></li>
                            <li data-tab-name="track-record"><a href="#trackRecord" aria-controls="track-record" role="tab"
                                                          data-toggle="tab">Track Record</a></li>
                            <li data-tab-name="review"><a href="#philanthropy" aria-controls="review" role="tab" data-toggle="tab">Philanthropy</a>
                            </li>
                            <li data-tab-name="additional-info"><a href="#contact" aria-controls="additional-info"
                                                                   role="tab" data-toggle="tab">Contact</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <!-- About -->
                        <div class="container single-tab-content tab-pane fade in active" id="about">
                            <p><?php echo $mydb->select_field("content", "tbl_about","id='1'");?></p>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 col-sm-offset-0 col-xs-offset-0">
                                    <img src="./admin/site_images/team/<?php echo $mydb->select_field("image", "tbl_team", "id='1'"); ?>" alt="<?php echo $mydb->select_field("title", "tbl_team", "id='1'"); ?>"/>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <?php echo $mydb->select_field("description", "tbl_team", "id='1'"); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- Our Team -->
                        <div class="container single-tab-content tab-pane fade" id="team">
                            <div class="clearfix"></div>
                            <?php
                            $mydb->where("id", "1", "!=");
                            $result = $mydb->select("tbl_team");
                            foreach ($result as $row):
                            ?>
                                <div class="row" style="margin-bottom: 2em;">
                                    <div class="col-md-3">
                                        <img src="./admin/site_images/team/<?php echo $row['image'] ?>" alt="<?php echo $row['title'] ?>"/>
                                    </div>

                                    <div class="col-md-9">
                                        <?php echo $row['description'] ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Track Record -->
                        <div class="container single-tab-content tab-pane fade " id="trackRecord">
                            <p class="read-more"><?php echo $mydb->select_field("content", "tbl_about","id='6'");?></p>
                        </div>
                        <!-- Philanthropy -->
                        <div class="container single-tab-content tab-pane fade " id="philanthropy">
                            <p class="read-more"><?php echo $mydb->select_field("content", "tbl_about","id='3'");?></p>
                        </div>

                        <!-- Contact -->
                        <div class="container text-center single-tab-content tab-pane fade " id="contact">
                            <div class="col-md-6" style="margin-top: 2em;">
                                <div class="sidebar-widget-wrapper text-left">
                                    <div class="single-sidebar-widget contact-widget">
                                        <div class="title">
                                            <h2><span><?php echo $mydb->select_field("title", "tbl_contact", "id='1'"); ?></span></h2>
                                        </div>
                                        <ul class="contact-infos text-left">
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
                                                        <a href="mailto:<?php echo $mydb->select_field("email", "tbl_contact", "id='1'"); ?>">
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
                </div>
                <!--- Tab Details --->
            </div>
        </div>
    </div>
    <!--- Tab Details --->

    <div class="clearfix"></div>
</div>

<!-- Featured-Carousel -->
<?php //require_once 'featured-programs.php'; ?>
<!-- div class="clearfix section-padding pb0"></div>
<!-- /Featured-Carousel -->

<!-- Footer -->
<?php require_once 'footer.php'; ?>
<!-- Footer -->

<script src="assets/jquery/jquery-1.11.3.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
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
<script src="js/readmore.min.js"></script>

<script type="text/javascript">
    $('.read-more').readmore({
        speed: 75,
        collapsedHeight: 200,
        moreLink: '<a href="#">Read more</a>'
        lessLink: '<a href="#">Read less</a>'
    });
</script>
</body>
</html>

