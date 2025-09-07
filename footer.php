<?php
if (isset($_POST['subscribe'])) {
    if ($_POST['email'] != "") {
        $post_mail = $_POST['email'];
        $exist_email = $mydb->select_field("email", "tbl_subscribers", "email = '$post_mail'");
        if ($exist_email != "") {
            echo "<script type='text/javascript'>sweetAlert('Oops','Email already exist!','error');</script>";
        } else {
            $insertData = array("email" => $_POST['email'], "created_at" => date('Y-m-d H:i:s'));
            $insert = $mydb->insert("tbl_subscribers", $insertData);
//$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if (@$insert) {
                echo "<script type='text/javascript'>swal('Thankyou!', 'For your Subscription!', 'success')</script>";
            } else {
                /* echo $url;
                print_r($mydb->error);*/
                echo "<script type='text/javascript'>sweetAlert('Please Try Again','something went wrong!','error');</script>";
            }
        }
    } else {
        echo "<script type='text/javascript'>sweetAlert('Oops','Invalid Email!','error');</script>";
    }
}
?>

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
                    <a href="./contact.php">
                        <div class="title">
                            <h2 class="text-center">contact<span> info</span></h2>
                        </div>
                    </a>
                    <!--<ul class="contact-infos">
                        <li>
                            <div class="icon-box">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="info-text">
                                <p><?php /*echo $mydb->select_field("address", "tbl_contact", "id='1'"); */?></p>
                            </div>
                        </li>
                        <li>
                            <div class="icon-box">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <p><?php /*echo $mydb->select_field("phone", "tbl_contact", "id='1'"); */?></p>
                            </div>
                        </li>
                        <li>
                            <div class="icon-box">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <p><?php /*echo $mydb->select_field("mobile", "tbl_contact", "id='1'"); */?></p>
                            </div>
                        </li>
                        <li>
                            <div class="icon-box">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="info-text">
                                <p>
                                    <a href="mailto:<?php /*echo $mydb->select_field("email", "tbl_contact", "id='1'"); */?>"><?php /*echo $mydb->select_field("email", "tbl_contact", "id='1'"); */?>
                                </p>
                            </div>
                        </li>
                    </ul>-->
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
          <!--<p>Coded with <i class="fa fa-heart" style="color: #e33a0c;"></i> by <a href="http://www.ktmrush.com" target="_blank">KTM Rush</a> / <a href="http://www.rajenshrestha.com.np" target="_blank">Rajen</a>.</p> -->
        </div>
    </div>
</section>
