<section class="gallery-wrapper section-padding pb0" xmlns="http://www.w3.org/1999/html">
    <div class=" container-fulid">
        <div class="container section-title text-center">
            <p>&nbsp;</p>
            <p>With Flawless knowledge of our destination and insider access opportunities to unique experiences, we
                at khumbila are committed to sharing the essence of the region-its culture, people and tradition-an
                experience that gurantees adventure, personal satisfaction, and self discovery.
            </p>
        </div>

        <div class="container section-title text-center">
            <h1 class="golden-text section-padding pb0">FEATURED PROGRAMS</h1>
        </div>

        <?php
        $mydb->where("featured", "1");
        $mydb->orderByCols = array("created_at desc");
        $result = $mydb->select("tbl_packages");
        if($mydb->totalRows > 0):
        ?>
            <div class="owl-carousel owl-theme" data-carousel-nav="false">
                <?php
                foreach ($result as $row):
                ?>
                <div class="item">
                    <div class="single-gallery-item">
                        <div class="img-box">
                            <img src="./admin/site_images/packages/thumbs/thumb_<?=$row['image'];?>" alt="<?=$row['title'];?>"/>
                            <div class="overlay">
                                <div class="box">
                                    <div class="content overlay-bg">
                                        <a href="./packages/<?=$row['slug'];?>"
                                           data-fancybox-group="service-gallery">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="overlay">
                                <div class="box">
                                    <div class="content overlay-bg">
                                        <a href="./packages/<?/*=$row['slug'];*/?>"
                                           data-fancybox-group="service-gallery">
                                            <div class="col-md-push-1 col-md-10">
                                                <h3><?/*=$row['title'];*/?></h3>
                                            </div>
                                            <div class="col-md-push-1 col-md-10">
                                                <div class="row">
                                                    <div class ="col-md-6 text-right"><?/*=$row['duration'];*/?></div>
                                                    <div class ="col-md-6 text-left">$ 1000</div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>-->
                        </div>

                        <a href="./packages/<?=$row['slug'];?>">
                            <div class="row" style="padding: 10px; color: #F9F9F9">
                                <div class ="col-md-12"><h5 style="margin: 0; padding: 0;"><?=$row['sub_title'];?></h5></div>
                                <div class ="col-md-12"><h5 class="yellow" style="margin-top: 5px; padding-top: 0;"><?=$row['title'];?></h5></div>
                            </div>

                            <div class="row" style="padding:  0 10px 10px;">
                                <div class ="col-md-6 text-left" style="color: #F9F9F9"><?=$row['duration'];?></div>
                                <div class ="col-md-6 text-right" style="color: #F9F9F9">From $<?=$row['price'];?></div>
                            </div>
                        </a>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <h3 class="text-center">No Packages To Display</h3>
            <?php endif; ?>
    </div>
</section>