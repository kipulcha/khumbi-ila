<?php

$id = @$_GET['id'];

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

    $image = $_FILES['image']['name'];

    if ($image) {

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

        $oldImage = $mydb->select_field("image", "tbl_services", "id='" . $id . "'");

        //update database with image
        $updateData = array("title" => $_POST['title'], "sub_title" => $_POST['sub_title'], "image" => $image_name, "updated" => $currentDate, "updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_slider", $updateData);

    } else {

        //update database without image
        $updateData = array("title" => $_POST['title'], "sub_title" => $_POST['sub_title'], "updated" => $currentDate, "updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_slider", $updateData);

    }

    if ($update) {
        if($image_name){
            unlink( './site_images/sliders/' . $oldImage );
            unlink( './site_images/sliders/' . 'small_' . $oldImage );
        }
        $mydb->redirect("./?page=slider&msg=updated");
    } else {
        $mydb->redirect("./?page=slider&msg=dberror");
    }

}


//Main Page
if (@$_GET['action']) {

//Sliders Delete

    if (@$_GET['action'] == 'delete') {
        $imageName = $mydb->select_field("image", "tbl_slider", "id='".$id."'");
        $mydb->where("id", $id);
        $delete =$mydb->delete("tbl_slider");

        if(@$delete) {
            unlink( './site_images/sliders/' . $imageName );
            unlink( './site_images/sliders/' . 'small_' . $imageName );
            $mydb->redirect("./?page=slider&msg=deleted");
        } else {
            $mydb->redirect("./?page=slider&msg=deleted-error");
    }}

    elseif (@$_GET['action'] == 'activate') {
        $activeSlideID = $mydb->select_field("id", "tbl_slider", "slide_status='1'");
        $updateData = array("slide_status" => '0', "updated" => $currentDate, "updated_by" => $_SESSION['username']);
        $mydb->where("id", $activeSlideID);
        $update = $mydb->update("tbl_slider", $updateData);

        $updateData = array("slide_status" => '1', "updated" => $currentDate, "updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_slider", $updateData);

        if ($update) {
            $mydb->redirect("./?page=slider&msg=activated");
        } else {
            $mydb->redirect("./?page=slider&msg=dberror");
        }

    }

    elseif (@$_GET['action'] == 'edit') {

//Sliders Edit
        ?>

        <div class="content-wrapper">

            <section class="content-header">

                <h1>Sliders&nbsp;&nbsp;<a href="./?page=slider">
                        <button class="btn btn-success" id="edit" title="Go Back"><i
                                class="fa fa-arrow-circle-left"></i> Go Back
                        </button>
                    </a>&nbsp;&nbsp;<a href="./?page=slider-add">
                        <button class="btn btn-primary" title="Add Sliders"><i class="fa fa-plus"></i> Add New</button>
                    </a></h1>

                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Sliders</a></li>
                    <li class="active"><a href="#">Edit Page</a></li>
                </ol>

            </section>

            <section class="content">

                <form id="slider-form" method="post" enctype="multipart/form-data" action="">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title" id='page-title'>Edit Sliders</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="box-body">

                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bullhorn"></i> Title</span>
                                            <input type="text" class="form-control" name="title"
                                                   placeholder="Slider's Title"
                                                   value="<?php echo $mydb->select_field("title", "tbl_slider", "id='" . $id . "'"); ?>"
                                                   required/>
                                        </div>
                                        <br>

                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bullhorn"></i> Sub-Title</span>
                                            <input type="text" class="form-control" name="sub_title"
                                                   placeholder="Slider's Sub-Title"
                                                   value="<?php echo $mydb->select_field("sub_title", "tbl_slider", "id='" . $id . "'"); ?>"
                                                   required/>
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-file-picture-o"></i> <?php echo $mydb->select_field("image", "tbl_slider", "id='" . $id . "'"); ?></span>
                                                    <input class="btn btn-info btn-flat" name="image" type="file"/>
                                                </div>
                                                <p><i class="fa fa-flag" aria-hidden="true"></i> 1300x500 px for optimized standard.</p>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i>
                                Save Changes
                            </button>
                            &nbsp;&nbsp;<a href="?page=slider" class="btn btn-danger"><i class="fa fa-remove"></i>
                                Cancel</a>
                        </div>
                    </div>
                </form>

            </section>

        </div>

    <?php }
} else { ?>

    <!--Sliders List-->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Sliders&nbsp;&nbsp;<a href="./?page=slider-add">
                    <button class="btn btn-primary" title="Add Sliders"><i class="fa fa-plus"></i> Add New</button>
                </a></h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Sliders</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Sliders List</h3>
                        </div>

                        <div class="box-body">

                            <?php if (@$_GET['msg'] == 'added') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Added!
                                    </h4>
                                    Sliders has been Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Updated!
                                    </h4>
                                    Sliders has been Updated.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Deleted!
                                    </h4>
                                    Sliders has been Deleted.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'dberror') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-warning"></i>
                                        Error!
                                    </h4>
                                    Data couldnot be added
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'activated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Activated!
                                    </h4>
                                    Selected Sliders has been Activated.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted-error') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-warning"></i>
                                        Error while Deleting Records!
                                    </h4>
                                    Data could not be Deleted.
                                </div>
                            <?php } ?>

                            <table id="contact-table" class="table table-bordered table-striped">

                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Inserted On</th>
                                    <th>Updated On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                $mydb->orderByCols = array("inserted desc");
                                $result = $mydb->select("tbl_slider");
                                $count = 1;
                                if ($mydb->totalRows > 0) {
                                    foreach ($result as $row):
                                        ?>

                                        <tr>
                                            <td class="text-center"><?=$count;?>.</td>
                                            <td><?= $row['title']; ?>
                                                &nbsp;&nbsp;<?php echo($row['slide_status'] == '1' ? '<span class="label label-success"><i class="fa fa-bell faa-ring animated"></i> active</span>' : '<span class="label label-danger">inactive</span>'); ?></td>
                                            <td class="text-center"><img src="site_images/sliders/small_<?= $row['image']; ?>"
                                                                         alt="no_image"/></td>
                                            <td class="text-center"><?= date("F d, Y", strtotime($row['inserted'])); ?><br/>
                                                <small
                                                    style="font-style:italic;"><?php echo($row['inserted_by'] ? "by " . $row['inserted_by'] : ""); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row['updated'] !== '0000-00-00 00:00:00') { ?>
                                                    <?php echo date("F d, Y", strtotime($row['updated'])); ?><br/>
                                                    <small
                                                        style="font-style:italic;"><?php echo($row['updated_by'] ? "by " . $row['updated_by'] : ""); ?></small>
                                                <?php } else { ?>
                                                    <?php echo '<i>No Updates Made'; ?>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <!--Edit-->
                                                <a href="./?page=slider&action=edit&id=<?= $row['id']; ?>">
                                                    <button type="button" class="btn btn-success btn-xs"><i class="fa fa-edit"></i> Edit
                                                    </button>
                                                </a>&nbsp;&nbsp;
                                                <!--Make Active-->
                                                <?php
                                                if($row['slide_status'] != '1' ):
                                                ?>
                                                    <a href="./?page=slider&action=activate&id=<?= $row['id']; ?>" data-toggle="modal"
                                                        class="btn btn-info btn-xs">
                                                        <i class="fa fa-refresh"></i>
                                                        Activate</a>&nbsp;&nbsp;
                                                <?php endif; ?>
                                                <!--Delete-->
                                                <a href="#delete" data-toggle="modal" data-id="<?= $row['id']; ?>"
                                                   id="delete<?= $row['id']; ?>" class="btn btn-danger btn-xs"
                                                   onClick="delete_slider(<?= $row['id']; ?>)"><i class="fa fa-trash"></i>
                                                    Delete</a>
                                            </td>
                                        </tr>

                                        <?php
                                        $count++;
                                    endforeach;
                                } else { ?>

                                    <tr>
                                        <td colspan="7" class="text-center" style="color:#903;">No Sliders Listed
                                        </td>
                                    </tr>

                                <?php } ?>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="7"><?php echo C_NAME; ?> Slider Table</th>
                                </tr>
                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </section>
    </div>

    <!--Delete Modal-->
    <script>
        function delete_slider(id) {
            var conn = './?page=slider&action=delete&id=' + id;
            $('#delete a').attr("href", conn);
        }
    </script>
    <div id="delete" class="modal">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3>Delete Slider</h3>
        </div>
        <div class="modal-body">
            <p>
                Are you sure you want to Delete Selected?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div>

<?php } ?>
<script type="text/javascript">
    $(function () {
        $('#contact-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    });
</script>