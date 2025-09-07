<?php
require_once './config.php';
?>
<!--
Author: Rajen Kaji Shrestha
Author URL: http://rajenshrestha.com.np
-->
<!DOCTYPE HTML>
<html>
<head>
    <title>Deals, Offers, Events, Places and Information Near Me</title>
    <meta name="description" content="Pandora List is a classified social platform for local communities and businesses. We connect local communities and businesses to buy or sell directly from each-other.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);
        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>

    <!-- favicon
    ============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="./images/icon.png">

    <!-- Bootstrap
    ============================================ -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css'/>

    <!-- Font Awesome
    ============================================ -->
    <link href="admin/dist/plugins/fontawesome/css/font-awesome.min.css" rel='stylesheet' type='text/css'/>

    <!-- Custom Theme files
    ============================================ -->
    <link href="css/style.css" rel='stylesheet' type='text/css'/>

    <!--Web font
    ============================================ -->
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href="http://fonts.googleapis.com/css?family=Anton" rel="stylesheet" type="text/css"/>
    <!--Mega Menu
    ============================================ -->
    <link href="css/megamenu.css" rel="stylesheet" type="text/css" media="all"/>

</head>

<body>
<div class="banner">
    <div class="container">
        <div class="logo">
            <a href="./"><img src="images/logo.png" alt="Sajilo Deals"/></a>
        </div>
        <div class="menu">
            <ul class="megamenu skyblue">
                <li class="active"><a href="./">Featured</a></li>
                <li><a href="trending">Trending</a></li>
                <li><a href="ending-soon">Ending Soon</a></li>
                <li><a href="advertise">Advertise</a></li>
                <div class="clearfix"></div>
            </ul>
        </div>
        <div class="clearfix"></div>
        <div class="header_arrow">
            <a href="#arrow" id="scrolldown">
                <svg class="arrows">
                    <path class="a1" d="M0 0 L30 32 L60 0"></path>
                    <path class="a2" d="M0 20 L30 52 L60 20"></path>
                    <path class="a3" d="M0 40 L30 72 L60 40"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
<div class="main">
    <div class="container">
        <div class="top_grid" id="arrow">
            <h1 class="retro">Goodies are on their Way</h1>
            <div class='clearfix'></div>
        </div>
    </div>

    <div class="content_middle_bottom">
        <div class="header-left" id="home">
            <section>
                <ul class="lb-album">
                    <?php
                    $categories = $mydb->select("tbl_categories");
                    if ($mydb->totalRows > 0) {
                        foreach ($categories as $row):
                            $count = 1;
                            ?>
                            <li>
                            <a href="category?category=<?= $row['slug']; ?>">
                                <img src="admin/site_images/categories/thumbs/big_<?= $row['image']; ?>"
                                     class="img-responsive" alt="image01"/>
                                <span><?= $row['title']; ?></span>
                            </a>
                            </li><?php
                        endforeach;
                    }
                    ?>
                </ul>
            </section>
        </div>
        <div class="field_content">
            <h1>Deals made in Heaven</h1>
            <h2>View Catagories</h2>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<!--Scripts
    ============================================ -->
<script type="text/javascript" src="js/jQuery-2.1.4.min.js"></script>
<script type="text/javascript" src="js/hover_pack.js"></script>
<script type="text/javascript" src="js/megamenu.js"></script>
<script>$(document).ready(function () {
        $(".megamenu").megamenu();
    });</script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#scrolldown").click(function (event) {
            event.preventDefault();
            $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
        });
    });
</script>

</body>
</html>
