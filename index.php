<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Khumbila Adventure Travel - Nepal Adventures</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="./img/icon.png">
    <meta name="description" content="We at Khumbi-Ila welcome you to embark on a journey that will leave an indelible mark on you. Nothing can capture the mysteries and magnificence of the Himalayas except experience.">
    <meta name="author" content="Rajen Kaji Shrestha">
    <meta name="robots" content="index,follow"/>
    <meta property="og:title" content="Khumbila Adventure Travel - Nepal Adventures"/>
    <meta property="og:image" content="http://www.khumbi-ila.com/admin/site_images/sliders/<?php echo $mydb->select_field("image", "tbl_slider", "slide_status='1'"); ?>"/>
    <meta property="og:description"
          content="We at Khumbi-Ila welcome you to embark on a journey that will leave an indelible mark on you. Nothing can capture the mysteries and magnificence of the Himalayas except experience."/>

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
                    <!--<li class="dropdown"><a href="#">Trip Extension</a>
                        <ul class="submenu text-left">
                            <?php
/*                                $mydb->where("type", "Trip Extension", "=");
                            $mydb->where("parent_id", 0, "=");
                            $mydb->where("display", "1", "=");
                            $result = $mydb->select("tbl_programs");
                            if($mydb->totalRows > 0):
                                foreach ($result as $row):
                                    */?>
                                    <li><a href="<?php /*echo($row['child'] == '0' ? './programs/'.$row['slug']:'#');*/?>" <?php /*echo($row['child'] == '1' ? 'class="mega-title"':''); */?>><?/*=$row['title'];*/?></a>
                                        <?php /*if($row['child'] == '1'): */?>
                                            <ul class="submenu">
                                                <?php
/*                                                    $mydb->where("parent_id", $row['id']);
                                                $subresult = $mydb->select("tbl_programs");
                                                foreach ($subresult as $subrow):
                                                    */?>
                                                    <li><a href="./programs/<?/*=$slug;*/?>"><?/*=$subrow['title'];*/?></a></li>
                                                <?php /*endforeach; */?>
                                            </ul>
                                        <?php /*endif; */?>
                                    </li>
                                <?php /*endforeach; */?>
                            <?php /*endif; */?>
                        </ul>
                    </li>-->
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
                    <!--<li><a href="contact">Contact Us</a></li>-->
                    <li><a href="enquire.php"><span class="inquire">Enquire</span></a></li>
                </ul>
            </div>

            <div class="nav-footer search">
                <ul class="list-inline">
                    <!--<li class="hidden-md hidden-lg hidden-sm">
                        <a href="#"><i class="icon icon-Search"></i></a>
                        <ul class="search-box">
                            <li>
                                <form action="./search" method="post">
                                    <input type="text" name="search" placeholder="Type and Hit Enter">
                                    <button type="submit"><i class="icon icon-Search"></i></button>
                                </form>
                            </li>
                        </ul>
                    </li>-->
                    <li><a href="#search"><i class="icon icon-Search"></i></a></li>
                    <li class="menu-expander hidden-lg hidden-md"><a href="#"><i class="icon icon-List"></i></a></li>
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

<!-- Landing Page -->
<section class="rev_slider_wrapper">
    <div id="slider1" class="rev_slider" data-version="5.0">
        <ul>
            <li data-transition="slidingoverlayleft">
                <img src="./admin/site_images/sliders/<?php echo $mydb->select_field("image", "tbl_slider", "slide_status='1'"); ?>" alt="Khumbila Adventures Travel- Travel Nepal" data-bgposition="top center" data-bgfit="cover"
                     data-bgrepeat="no-repeat" data-bgparallax="2">
                <!--<div class="tp-caption sfl tp-resizeme caption-label"
                     data-x="left" data-hoffset="0"
                     data-y="top" data-voffset="280"
                     data-whitespace="nowrap"
                     data-transform_idle="o:1;"
                     data-transform_in="o:0"
                     data-transform_out="o:0"
                     data-start="500">
                    Nepal
                </div>-->
                <div class="tp-caption sfr tp-resizeme caption-h1"
                     data-x="center" data-hoffset="0"
                     data-y="top" data-voffset="['180', '180', '180', '120']"
                     data-whitespace="nowrap"
                     data-transform_idle="o:1;"
                     data-transform_in="o:0"
                     data-transform_out="o:0"
                     data-start="1000"
                     data-fontsize="['48', '36', '30', '28']"
                     data-lineheight="['46', '36', '32', '28']" >
                    <?php echo $mydb->select_field("title", "tbl_slider", "slide_status='1'"); ?>
                </div>
                <div class="tp-caption sfr tp-resizeme caption-p"
                     data-x="center" data-hoffset="0"
                     data-y="top" data-voffset="['240', '240', '240', '160']"
                     data-whitespace="nowrap"
                     data-transform_idle="o:1;"
                     data-transform_in="o:0;t:1000"
                     data-transform_out="o:0"
                     data-start="1500"
                     data-fontsize="['36', '30', '28', '28']"
                     data-lineheight="['36', '32', '28', '28']" >
                    <?php echo $mydb->select_field("sub_title", "tbl_slider", "slide_status='1'"); ?>
                </div>
                <div class="tp-caption sfr tp-resizeme "
                     data-x="center" data-hoffset="0"
                     data-y="top" data-voffset="['310', '310', '310', '200']"
                     data-whitespace="nowrap"
                     data-transform_idle="o:1;"
                     data-transform_in="o:0;t:1000"
                     data-transform_out="o:0"
                     data-start="2000"
                     data-fontsize="['13', '13', '13', '13']"
                     data-lineheight="['13', '13', '13', '13']" >
                        <div class="slider-btn-text">
                        <a href="./enquire.php">Plan my Trip &nbsp;&nbsp;&nbsp;<i
                                    class="fa fa-angle-right"></i></a>
                        </div>

                     <!--<a href="./enquire" class="thm-btn hvr-sweep-to-top text-center">Plan my Trip&nbsp;&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i></a>-->
                </div>
            </li>
        </ul>
    </div>
</section>
<!-- /Landing Page -->

<!--About Area -->
<div class="about-area text-center about-area-three">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="about-container section-padding">

                    <div class="section-title title-three">
                        <div class="title-border">
                            <h1 class="golden-text">
                                HELLO / NAMASTE
                                <?php
                                /*
                                 * @todo:Adding 2 color Heading
                                 * $aboutSubTitle  = $mydb->select_field("sub_title", "tbl_about","id='1'");
                                    $aboutWords = explode(" ", $aboutSubTitle );
                                    for($i=0;$i<count($aboutWords);$i++):
                                        if($i == 0) {
                                            echo '<span>'.$aboutWords[$i].'</span>';
                                        } else {
                                            if($aboutWords[$i] != null):
                                                echo $aboutWords[$i];
                                            endif;
                                        }
                                    endfor;
                                */
                                ?>
                            </h1>
                        </div>
                    </div>

                    <div class="about-text home-about-text">
                        <p>We at Khumbi-Ila welcome you to embark on a journey that will leave an indelible mark on you. Nothing can capture the mysteries and magnificence of the Himalayas except experience.</p>
                        <div class="clearfix"></div>
                        <a href="about.php">More &nbsp;&nbsp;&nbsp;<i
                                class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of About Area-->

<!-- Home Content -->
<section class="welcome-section pb0">
    <div class="col-md-12">
        <div class="gallery-widget">
            <ul class="list-inline">
                <?php
                $mydb->orderByCols = array("created_at");
                $mydb->where("child",0);
                $mydb->where("display",1);
                $mydb->andOrOperator = "OR";
                $mydb->where("title", 'Nepal');
                $result = $mydb->select("tbl_programs");
                if($mydb->totalRows > 0):
                    foreach ($result as $row):
                    ?>
                    <a href="./programs.php?slug=<?=$row['slug'];?>">
                        <li class="col-md-2 col-sm-3 col-xs-6"><img
                                src="./admin/site_images/programs/thumbs/big_<?=$row['image'];?>"
                                alt="<?=$row['title'];?>" class="img-responsive"/>
                            <h2><?=$row['title'];?></h2>
                        </li>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h3 class="text-center">No Packages To Display</h3>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>
<div class="clearfix"></div>
<!-- /Home Content -->

<!-- Featured-Carousel -->
<?php /*require_once 'featured-programs.php';*/ ?>
<!-- /Featured-Carousel -->

<!--
Values
add class latest-news testimonials-carousel blog-2-col blog-3-col to section for sepia values effect
-->
<section class="" style="padding-top: 3em !important;">
    <div class="container">
        <div class="section-title text-center">
            <p style=""><?php echo strip_tags($mydb->select_field("sub_title", "tbl_about","id='2'"));?> </p>
        </div>
        <!--<div class="row">
            <?php
/*            $mydb->orderByCols = array("created_at asc");
            $result = $mydb->select("tbl_values");
            $mydb->limit = "0,3";
            foreach ($result as $row):
            */?>
            <a href="./our-values">
                <div class="col-md-3 col-sm-3 hidden-xs" style="margin: 0 4%">
                    <div class="single-blog-post">
                        <div class="img-box">
                            <img src="./admin/site_images/values/thumbs/thumb_<?/*=$row['image'];*/?>" class="img-responsive" alt="Khumbila Adventures Travel- Travel Nepal">
                            <div class="overlay clearfix">
                                <div class="inner-box">
                                    <div class="box">
                                        <h3>
                                            <?php
/*                                            $word = explode(' ' ,$row['title']);
                                            echo $word[0] .'<br>'. $word[1];
                                            */?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php /* endforeach; */?>
        </div>-->
    </div>
</section>
<!-- /Values -->

<!-- Newsletter >
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

</body>
</html>