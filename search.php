<!DOCTYPE html>
<?php require_once 'config.php';
$searchInput = $_POST['search'];
?>

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
                                    <li><a href="<?php echo($row['child'] == '0' ? './programs/'.$row['slug']:'#');?>" <?php echo($row['child'] == '1' ? 'class="mega-title"':''); ?>><?=$row['title'];?></a>
                                        <?php if($row['child'] == '1'): ?>
                                            <ul class="submenu">
                                                <?php
                                                $mydb->where("parent_id", $row['id']);
                                                $mydb->where("display", "1", "=");
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
                                $mydb->orderByCols = array("created_at");
                                $mydb->where("display", "1", "=");
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
                        <!--<li><a href="contact">Contact Us</a></li>-->
                        <li><a href="enquire"><span class="inquire">Enquire</span></a></li>
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
        <form action="./search" method="post">
            <input type="search" name="search" placeholder="type keyword(s) here" />
            <button type="submit" class="btn btn-primary">Search <i class="icon icon-Search"></i></button>
        </form>
    </div>
</header>
<!-- /Menubar -->

<!-- Page Header -->
<section class="inner-banner" style="padding-bottom: 2px;">
    <div class="container text-center">
        <h2><span>SEARCH</span></h2>
    </div>
</section>
<!-- /Page Header -->

<!-- Searech -->
<?php
$count = 0;
$sql = $mydb->executeQuery("SELECT * FROM tbl_packages WHERE (title LIKE '%$searchInput%' OR slug LIKE '%$searchInput%')");
if ($mydb->totalRows > 0) {
    $count = 1;
    $type = "packages";
    /*echo $type;*/
    $result = $sql;
} else {
    $sql = $mydb->executeQuery("SELECT * FROM tbl_services WHERE (title LIKE '%$searchInput%' OR slug LIKE '%$searchInput%')");
    if ($mydb->totalRows > 0) {
        $count = 1;
        $type = "services";
        /*echo $type;*/
        $result = $sql;

    } else {
        $sql = $mydb->executeQuery("SELECT * FROM tbl_packages WHERE (overview LIKE '%$searchInput%')");
        if ($mydb->totalRows > 0) {
            $count = 1;
            $type = "packages";
            /*echo $type;*/
            $result = $sql;

        } else {
            $sql = $mydb->executeQuery("SELECT * FROM tbl_services WHERE (content LIKE '%$searchInput%')");
            if ($mydb->totalRows > 0) {
                $count = 1;
                $type = "services";
                /*echo $type;*/
                $result = $sql;
            }

        }
    }
}
?>

<!-- Page Body -->
<!-- Search Content -->

<section class="gallery-wrapper section-padding pb0" xmlns="http://www.w3.org/1999/html">
    <div class=" container-fulid">
        <div class="container section-title text-center">
            <h1 class="golden-text section-padding pb0"><?= $_POST['search'] ?></h1>
        </div>
        <section class="welcome-section pb0">
            <div class="col-md-12">
                <div class="footer-widget gallery-widget">
                    <ul class="list-inline">
                        <?php
                        if ($count == 1) {

                            foreach ($result as $row):
                                ?>
                                <a href="./<?= $type ?>/<?= $row['slug']; ?>">
                                    <li class="col-md-2 col-sm-3 col-xs-6"><img
                                            src="./admin/site_images/<?= $type ?>/thumbs/<?php echo($type == 'packages' ? 'thumb' : 'big'); ?>_<?= $row['image']; ?>"
                                            alt="<?= $row['title']; ?>" class="img-responsive"/>
                                        <h2><?= $row['title']; ?></h2>
                                    </li>
                                </a>
                            <?php endforeach;
                        } else {
                            echo "<h3 class='text-center'>No Result Found</h3>";
                        }
                        ?>

                    </ul>
                </div>
            </div>
        </section>
        <div class="clearfix"></div>
    </div>
</section>
<!-- //Search Content -->

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