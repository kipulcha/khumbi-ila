<?php
/**
 ** INSERT Script
 **/
if (isset($_POST['submit'])) {

    extract($_POST);

    //Make Thumb Script
    function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 100)
    {
        $imgsize = getimagesize($source_file);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 8;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 90;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if ($width_new > $width) {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);

        if ($dst_img) imagedestroy($dst_img);
        if ($src_img) imagedestroy($src_img);
    }

    //Get Image Extension Script
    function getExtension($str)
    {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    //Slug
    require_once './pages/slug.php';
    $slug = slug($title, 'tbl_packages', '');

    //Image Upload
    $filename = stripslashes($_FILES['image']['name']);

    $extension = getExtension($filename);
    $extension = strtolower($extension);

    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
        $mydb->redirect("./?page=packages&msg=image_error_extension");
    } else {
        $image_name = $slug . time() . '.' . $extension;
        $newname = "site_images/packages/" . $image_name;
        $copied = copy($_FILES['image']['tmp_name'], $newname);

        //resize image
        resize_crop_image(800, 500, "site_images/packages/" . $image_name, "site_images/packages/thumbs/thumb_" . $image_name);
        resize_crop_image(80, 50, "site_images/packages/" . $image_name, "site_images/packages/thumbs/small_" . $image_name);
    }

    if(!@$featured){
        $featured = 0;
    }
    $item_order = $mydb->select_field("MAX(item_order)", "tbl_packages");
    $item_order = $item_order+1;
    /*echo $item_order;*/
    $programs = json_encode($programs);

    //Insert Data
    $insertData = array("title" => $title, "slug" => $slug,"sub_title" => $sub_title, "price" => $price, "programs" => $programs, "image" => $image_name, "duration" => $duration, "dates" => $dates, "grade" => $grade, "youtube_id" => $youtube_id, "featured" => $featured, "prices" => $prices, "item_order" => $item_order, "information" => $information, "overview" => $overview, "itinerary" => $itinerary, "created_at" => $currentDate, "created_by" => $_SESSION['username'], "updated_at" => '', "updated_by" => '');
    $insert = $mydb->insert("tbl_packages", $insertData);


    if (@$insert) {
        $mydb->redirect("./?page=packages&msg=added");
    } else {
        $mydb->redirect("./?page=packages&msg=dberror");
    }

}

?>

<!--==================================================
Main Content Start
===================================================-->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Packages&nbsp;&nbsp;<a href="./?page=packages" class="btn btn-success" title="Go to List"><i
                    class="fa fa-arrow-circle-left"></i> Go to List</a></h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Packages</li>
            <li class="active">Add New</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    <!--==================================================
    Form Start
    ===================================================-->
        <form id="packages-form" method="post" enctype="multipart/form-data" action="">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Add Packages</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="box-body">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bullhorn"></i>  Title</span>
                                            <input type="text" class="form-control" name="title"
                                                   placeholder="Packages Title" required/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><input name="featured"
                                                                                                        type="checkbox"
                                                                                                        value="1"/></span>
                                            <input type="text" class="form-control" value="Featured Packages"
                                                   readonly/>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bullhorn"></i> Sub Title</span>
                                            <input type="text" class="form-control" name="sub_title"
                                                   placeholder="Packages Sub Title"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bookmark"></i>  Grade</span>
                                            <input type="text" class="form-control" name="grade"
                                                   placeholder="Grade"/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">                                    
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-clock-o"></i> Duration</span>
                                            <input type="text" class="form-control" name="duration"
                                                   placeholder="Duration in Days"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-usd"></i>  Price</span>
                                            <input type="text" class="form-control" name="price"
                                                   placeholder="Price" />
                                        </div>
                                    </div>

                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-calendar"></i> Package Dates:</span>
                                            <input type="text" class="form-control" name="dates"
                                                   placeholder="Package Dates" />
                                        </div>
                                        <p><small><b>Note:</b> Please use comma(,) for separating dates.</small></p>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group form-group"><span class="input-group-addon"><i
                                                    class="fa fa-file-picture-o"></i>  Image</span>
                                            <input class="btn btn-info btn-flat" name="image" type="file"/>
                                        </div>
                                        <p><small><b>Note:</b> 800x800px image recommended.</small></p>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-youtube"></i> Youtube ID</span>
                                            <input type="text" class="form-control" name="youtube_id"
                                                   placeholder="Youtube URL ID" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <select class="form-control select2" name="programs[]" multiple="multiple" data-placeholder="Select programs" style="width: 100%;" required>
                                            <?php
                                            $mydb->where("child","0");
                                            $result = $mydb->select("tbl_programs");
                                            if ($mydb->totalRows > 0) {
                                                foreach ($result as $row):
                                                    ?>
                                                    <option
                                                        value="<?= $row['id']; ?>"><?= $row['title']; ?></option>
                                                <?php endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                                    <textarea class="ckeditor" name="overview" placeholder="Overview" id="editor1"
                                                              >Overview</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group">
                                                    <textarea class="ckeditor" name="itinerary" id="editor1"
                                                              >Itinerary</textarea>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group">
                                                    <textarea class="ckeditor" name="prices" id="editor1"
                                                              >Prices</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group">
                                                    <textarea class="ckeditor" name="information" id="editor1"
                                                              >Necessary Info</textarea>
                                        </div>
                                    </div>

                                </div>
                                <br>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Packages
                    </button>
                    &nbsp;&nbsp;<a href="?page=packages" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            
            </div>
        </form>
    </section>
</div>
<!--
Initialize Multi Select
-->
<script>
    $(function () {
        $(".select2").select2();
    });
</script>
