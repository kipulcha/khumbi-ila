<?php
require_once 'config.php';
$id = $mydb->select_field("id", "tbl_programs", "slug='" . $_GET['slug'] . "'");
$childCheck = $mydb->select_field("child", "tbl_programs", "slug='" . $_GET['slug'] . "'");
/*echo $childCheck;*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $mydb->select_field("title", "tbl_programs", "id='" . $id . "'"); ?> - Khumbila Adventures</title>

    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../img/icon.png">
    <meta name="description"
          content="<?php echo $mydb->select_field("title", "tbl_programs", "id='" . $id . "'"); ?>">
    <meta name="author" content="Rajen Kaji Shrestha">
    <meta name="robots" content="index,nofollow"/>
    <meta property="og:title" content="<?= $mydb->select_field("title", "tbl_programs", "id='" . $id . "'"); ?> - Khumbila Adventures"/>
    <meta property="og:image" content="http://www.khumbila-tours.com/admin/site_images/programs/<?= $mydb->select_field("image", "tbl_programs", "id='" . $id . "'"); ?>"/>
    <meta property="og:description"
          content="<?php echo strip_tags($mydb->select_field("content", "tbl_programs", "id='" . $id . "'")); ?>"/>
    <!--mobile responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- main stylesheet -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">

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
                                    <li><a href="<?php echo($row['child'] == '0' ? './programs.php?slug='.$row['slug']:'#');?>" <?php echo($row['child'] == '1' ? 'class="mega-title"':''); ?>><?=$row['title'];?></a>
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
                        <li class=""><a href="#search"><i class="icon icon-Search"></i></a></li>
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

<div class="section-padding">
    <!-- Home Content -->
    <section class="welcome-section">
        <div class="container">
            <div class="col-md-12 col-sm-12">
                <?php echo $mydb->select_field("content", "tbl_programs", "id='" . $id . "'"); ?>
            </div>
        </div>
    </section>
    <!-- /Home Content -->

    <div class="clearfix"></div>

    <!-- Packages/Programs -->
    <section class="welcome-section container" id="items">
        <?php
        $count = 0;
        // Packages Check
        if ($childCheck == 0) {
            /*Programs that doesnot have child*/
            /*fetch packages that are related to that programs*/
            $mydb->orderByCols = array("item_order");
            $result = $mydb->select("tbl_packages");
            foreach ($result as $row):
                if (in_array($id, json_decode($row['programs']))):
                $price = $row['price'];
                $duration = $row['duration'];
        ?>
                    <a href="../packages.php?slug=<?= $row['slug']; ?>" class="blue">
                        <div class="col-md-6 col-sm-6 col-xs-12 center-block">
                                <img src="../admin/site_images/packages/thumbs/thumb_<?= $row['image']; ?>" class="img-responsive" alt="<?= $row['title']; ?>" title="<?= $row['title']; ?>"/>
                                <h3 class="text-center cap-caption mtop5"><?= $row['title']; ?>
                                    <span>
                                        <?php echo( $duration ? '- '.$duration.' days':'' ); ?>
                                        <?php echo( $price ? '- $'.$price : '' ); ?>
                                    </span>
                                </h3>
                        </div>
                    </a>
                <?php $count++; ?>
                <?php endif; ?>
            <?php endforeach;
        } // Programs Check
        elseif ($childCheck == 1) {
            /*Programs that have child*/
            /*fetch programs that are related to that programs*/
            $mydb->where("parent_id", $id);
            $mydb->orderByCols = array("created_at");
            $result = $mydb->select("tbl_programs");
            foreach ($result as $row):
                ?>
                <a href="../programs.php?slug=<?= $row['slug']; ?>">
                        <div class="col-md-6 col-sm-6 col-xs-12 center-block mtop25">
                            <img src="../admin/site_images/programs/thumbs/thumb_<?= $row['image']; ?>" class="img-responsive" alt="<?= $row['title']; ?>" title="<?= $row['title']; ?>"/>
                            <h3 class="text-center cap-caption mtop5"><?= $row['title']; ?></h3>
                    </div>
                </a>
                <?php $count++; ?>
            <?php endforeach;
        }
        ?>

        <p>&nbsp;</p>

        <div class="center-block section-padding text-center" id="paginate"></div>

    </section>
    
    <div class="clearfix"></div>
</div>

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
<script type="text/javascript" src="../js/easypaginate.js"></script>
<script type="text/javascript">
    jQuery(function($){ 
        $('section#items').easyPaginate({
          step: 10,
          obj: "#paginate"
        });

        
    }); 
    /*jQuery(function($){ 
        $('ul#cat-items').easyPaginate({
           step: 8,
        });

        
    }); */

    </script>

</body>
</html>
