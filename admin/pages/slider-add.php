<?php
if (isset($_POST['submit'])) {

//get extension
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


//resize and crop image by center
    function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 90)
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

    $filename = stripslashes($_FILES['image']['name']);

    $extension = getExtension($filename);
    $extension = strtolower($extension);

    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
        $mydb->redirect("./?page=slider&msg=image_error_extension");
    } else {

        $image_name = date('ymd') . time() . '.' . $extension;
        $newname = "site_images/sliders/" . $image_name;
        $copied = copy($_FILES['image']['tmp_name'], $newname);

        //resize slider image
        resize_crop_image(1300, 500, "site_images/sliders/" . $image_name, "site_images/sliders/" . $image_name);

        resize_crop_image(130, 50, "site_images/sliders/" . $image_name, "site_images/sliders/small_" . $image_name);

    }

    $activeSlideID = $mydb->select_field("id", "tbl_slider", "slide_status='1'");
    $insertData = array("title" => $_POST['title'], "sub_title" => $_POST['sub_title'], "slide_status" => $_POST['slide_status'], "image" => $image_name, "inserted" => $currentDate, "inserted_by" => $_SESSION['username'], "updated" => '', "updated_by" => '');
    $insert = $mydb->insert("tbl_slider", $insertData);
    
    if ($insert) {
        if($_POST['slide_status'] == '1'){
            $updateData = array("slide_status" => '0', "updated" => $currentDate, "updated_by" => $_SESSION['username']);
            $mydb->where("id", $activeSlideID);
            $update = $mydb->update("tbl_slider", $updateData);
        }
        $mydb->redirect('./?page=slider&msg=added');
    } else {
        $mydb->redirect("./?page=slider&msg=dberror");
    }

}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Sliders&nbsp;&nbsp;<a href="./?page=slider" class="btn btn-success" title="Go to List"><i
                    class="fa fa-arrow-circle-left"></i> Go to List</a></h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Sliders</li>
            <li class="active">Add New</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Contact Form -->
        <form id="slider-form" method="post" enctype="multipart/form-data" action="">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Add Sliders</h3>
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

                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-bullhorn"></i> Title</span>
                                    <input type="text" class="form-control" name="title" placeholder="Slider Title"
                                           required/>
                                </div>
                                <br>

                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-bullhorn"></i> Sub-Title</span>
                                    <input type="text" class="form-control" name="sub_title"
                                           placeholder="Slider Sub-Title" required/>
                                </div>
                                <br>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-file-picture-o"></i> Image</span>
                                            <input class="btn btn-info btn-flat" name="image" type="file" required/>                                            
                                        </div>
                                        <p><i class="fa fa-flag" aria-hidden="true"></i> 1300x500 px for optimized standard.</p>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><input name="slide_status"
                                                                                                        type="checkbox"
                                                                                                        value="1"/></span>
                                            <input type="text" class="form-control" value="Display" readonly/>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Slider
                    </button>
                    &nbsp;&nbsp;<a href="?page=slider" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>