<?php

if (isset($_POST['submit'])) {

    //image upload
    require_once './pages/slug.php';
    $slug = slug($_POST['title']);

    function make_thumb($img_name, $filename, $new_w, $new_h)
    {

        $ext = getExtension($img_name);

        if (!strcmp("jpg", $ext) || !strcmp("jpeg", $ext))
            $src_img = imagecreatefromjpeg($img_name);

        if (!strcmp("png", $ext))
            $src_img = imagecreatefrompng($img_name);

        $old_x = imageSX($src_img);
        $old_y = imageSY($src_img);

        $ratio1 = $old_x / $new_w;
        $ratio2 = $old_y / $new_h;
        if ($ratio1 > $ratio2) {
            $thumb_w = $new_w;
            $thumb_h = $old_y / $ratio1;
        } else {
            $thumb_h = $new_h;
            $thumb_w = $old_x / $ratio2;
        }

        $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);

        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

        if (!strcmp("png", $ext))
            imagepng($dst_img, $filename);
        else
            imagejpeg($dst_img, $filename);

        imagedestroy($dst_img);
        imagedestroy($src_img);
    }

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

    $errors = 0;

    $filename = stripslashes($_FILES['image']['name']);

    $extension = getExtension($filename);
    $extension = strtolower($extension);

    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png")) {
        $mydb->redirect("./?page=expeditions&msg=image_error_extension");
    } else {

        $image_name = date('ymd') . time() . '.' . $extension;

        $newname = "site_images/expeditions/" . $image_name;
        $copied = copy($_FILES['image']['tmp_name'], $newname);

        if (!$copied) {
            $mydb->redirect("./?page=expeditions&msg=image_copy_error");
        } else {

            $thumb_name = 'site_images/expeditions/thumbs/thumb_' . $image_name;

            $thumb = make_thumb($newname, $thumb_name, 600, 480);

            $thumb_name = 'site_images/expeditions/thumbs/small_' . $image_name;

            $thumb = make_thumb($newname, $thumb_name, 100, 80);

        }
    }

    if($_POST['featured']){
        $featured = $_POST['featured'];
    } else {
        $featured = 0;
    }

    $insertData = array("title" => $_POST['title'], "slug" => $slug, "category" => $_POST['category'], "featured" => $featured, "height" => $_POST['height'], "latitude" => $_POST['latitude'], "longitude" => $_POST['longitude'], "route" => $_POST['route'], "caravan_route" => $_POST['caravan_route'], "duration" => $_POST['duration'], "fly_route" => $_POST['fly_route'], "video_url" => $_POST['video_url'], "image" => $image_name, "cost_includes" => $_POST['cost_includes'], "cost_excludes" => $_POST['cost_excludes'], "description" => $_POST['description'], "itinerary" => $_POST['itinerary'], "created_at" => $currentDate, "created_by" => $_SESSION['username'], "updated_at" => '', "updated_by" => '');
    $insert = $mydb->insert("tbl_expeditions", $insertData);

    if (@$insert) {
        $mydb->redirect("./?page=expeditions&msg=added");
    } else {
        $mydb->redirect("./?page=expeditions&msg=dberror");
    }

}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Expeditions&nbsp;&nbsp;<a href="./?page=expeditions" class="btn btn-success" title="Go to List"><i
                    class="fa fa-arrow-circle-left"></i> Go to List</a></h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Expeditions</li>
            <li class="active">Add New</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Contact Form -->
        <form id="expeditions-form" method="post" enctype="multipart/form-data" action="">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Add Expeditions</h3>
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
                                                   placeholder="Expeditions Title" required/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><input name="featured"
                                                                                                        type="checkbox"
                                                                                                        value="1"/></span>
                                            <input type="text" class="form-control" value="Featured Expeditions"
                                                   readonly/>
                                        </div>
                                    </div>

                                    <!--<div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><input name="main"
                                                                                                        type="checkbox"
                                                                                                        value="1"/></span>
                                            <input type="text" class="form-control" value="Display as Main"
                                                   readonly/>
                                        </div>
                                    </div>-->

                                </div>
                                <br>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-calendar"></i> Duration</span>
                                            <input type="text" class="form-control" name="duration"
                                                   placeholder="Duration in Days" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bookmark"></i>  Altitude</span>
                                            <input type="text" class="form-control" name="height"
                                                   placeholder="Altitude/Height" />
                                        </div>
                                    </div>

                                </div>
                                <br>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bookmark"></i>  Latitude</span>
                                            <input type="text" class="form-control" name="latitude"
                                                   placeholder="Latitude" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bookmark"></i> Longitude</span>
                                            <input type="text" class="form-control" name="longitude"
                                                   placeholder="Longitude" />
                                        </div>
                                    </div>

                                </div>
                                <br>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group form-group"><span class="input-group-addon"><i
                                                    class="fa fa-file-picture-o"></i>  Image</span>
                                            <input class="btn btn-info btn-flat" name="image" type="file"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-youtube"></i>  Youtube ID</span>
                                            <input type="text" class="form-control" name="video_url"
                                                   placeholder="Youtube URL ID" />
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-arrow-up"></i>  Ascent Route</span>
                                            <input type="text" class="form-control" name="route"
                                                   placeholder="Ascent Route" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-road"></i> Caravan Route</span>
                                            <input type="text" class="form-control" name="caravan_route"
                                                   placeholder="Caravan Route" />
                                        </div>
                                    </div>

                                </div>
                                <br>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-plane"></i>  Fly Route</span>
                                            <input type="text" class="form-control" name="fly_route"
                                                   placeholder="Fly in/Fly out" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-compass"></i>  Expedition Type</span>
                                            <select name="category" class="form-control" required>
                                                <option disabled selected>--Select One--</option>
                                                <option value="1">Nepal Expedition</option>
                                                <option value="2">Tibet Expedition</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-newspaper-o"></i> Description </span>
                                                    <textarea class="ckeditor" name="description" id="editor1"
                                                              required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-newspaper-o"></i>  Itinerary </span>
                                                    <textarea class="ckeditor" name="itinerary" id="editor1"
                                                              required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-newspaper-o"></i>  Cost Includes</span>
                                                    <textarea class="ckeditor" name="cost_includes" id="editor1"
                                                              required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-newspaper-o"></i>   Cost Excludes </span>
                                                    <textarea class="ckeditor" name="cost_excludes" id="editor1"
                                                              required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <br>

                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Expeditions
                    </button>
                    &nbsp;&nbsp;<a href="?page=expeditions" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>