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
        $mydb->redirect("./?page=past-events&msg=image_error_extension");
        exit;
    } else {

        $image_name = date('ymd') . time() . '.' . $extension;

        $newname = "site_images/past-events/" . $image_name;
        $copied = copy($_FILES['image']['tmp_name'], $newname);

        if (!$copied) {
            $mydb->redirect("./?page=past-events&msg=image_copy_error");
            exit;
        } else {

            $thumb_name = 'site_images/past-events/thumbs/thumb_' . $image_name;

            $thumb = make_thumb($newname, $thumb_name, 600, 480);

            $thumb_name = 'site_images/past-events/thumbs/small_' . $image_name;

            $thumb = make_thumb($newname, $thumb_name, 100, 80);

        }
    }

    if($_POST['display']){
        $display = $_POST['display'];
    } else {
        $display = 0;
    }

    $insertData = array("title" => $_POST['title'], "slug" => $slug, "display" => $display, "image" => $image_name, "location" => $_POST['location'], "season" => $_POST['season'], "description" => $_POST['description'], "group_members" => $_POST['group_members'], "created_at" => date('Y-m-d H:i:s'), "created_by" => $_SESSION['username'], "updated_at" => '', "updated_by" => '');
    $insert = $mydb->insert("tbl_past_events", $insertData);

    if (@$insert) {
        $mydb->redirect("./?page=past-events&msg=added");
    } else {
        $mydb->redirect("./?page=past-events&msg=dberror");
    }

}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Past Events&nbsp;&nbsp;<a href="./?page=past-events" class="btn btn-success" title="Go to List"><i
                    class="fa fa-arrow-circle-left"></i> Go to List</a></h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Past Events</li>
            <li class="active">Add New</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Contact Form -->
        <form id="past-events-form" method="post" enctype="multipart/form-data" action="">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Add Past Events</h3>
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
                                                    class="fa fa-user"></i>  Title</span>
                                            <input type="text" class="form-control" name="title"
                                                   placeholder="Past Events Title" required/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><input name="display"
                                                                                                        type="checkbox"
                                                                                                        value="1"/></span>
                                            <input type="text" class="form-control" value="Show Event"
                                                   readonly/>
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
                                                    class="fa fa-map-marker"></i>  Location</span>
                                            <input type="text" class="form-control" name="location"
                                                   placeholder="Past Events Location" required/>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-map-marker"></i>  Season/Year</span>
                                            <input type="text" class="form-control" name="season"
                                                   placeholder="Past Events Season/Year" required/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-list"></i> Category</span>
                                            <select name="category" class="form-control" >
                                                <option value="0">Expedition</option>
                                                <option value="1">Trekking</option>
                                                <option value="2">Peak Climbing</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <br>

                                <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-newspaper-o"></i>  Description </span>
                                            <textarea class="ckeditor" name="description" id="editor1"
                                                      required></textarea>
                                </div>
                                <br>

                                <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-users"></i> Group Members</span>
                                            <textarea class="ckeditor" name="group_members" id="editor1"
                                                      required></textarea>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Partners
                    </button>
                    &nbsp;&nbsp;<a href="?page=past-events" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>