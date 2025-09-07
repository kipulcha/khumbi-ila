<?php

$id = @$_GET['id'];

if (isset($_POST['submit'])) {

    //image functions
    require_once './pages/slug.php';
    $slug = slug($_POST['title']);

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

    $errors = 0;

    $image = $_FILES['image']['name'];


    if ($image) {

        //Image Upload
    $filename = stripslashes($_FILES['image']['name']);

    $extension = getExtension($filename);
    $extension = strtolower($extension);

    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
        $mydb->redirect("./?page=program-headers&msg=image_error_extension");
    } else {
        $image_name = $slug . time() . '.' . $extension;
        $newname = "site_images/services/" . $image_name;
        $copied = copy($_FILES['image']['tmp_name'], $newname);

        //resize slider image
        resize_crop_image(1024, 384, "site_images/services/" . $image_name, "site_images/services/thumbs/thumb_" . $image_name);
        resize_crop_image(786, 680, "site_images/services/" . $image_name, "site_images/services/thumbs/big_" . $image_name);
        resize_crop_image(102, 38, "site_images/services/" . $image_name, "site_images/services/thumbs/small_" . $image_name);
    }

        $oldImage = $mydb->select_field("image", "tbl_services", "id='" . $id . "'");
        //update database with image
        $updateData = array("title" => $_POST['title'], "slug" => $slug, "image" => $image_name, "display" => $_POST['display'], "content" => $_POST['content'],"updated_at" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_services", $updateData);

    } else {

        //update database without image
        $updateData = array("title" => $_POST['title'], "slug" => $slug, "display" => $_POST['display'], "content" => $_POST['content'],"updated_at" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_services", $updateData);

    }
    
    if (@$update) {
        if(@$image_name){
            unlink( './site_images/services/' . $oldImage );
            unlink( './site_images/services/thumbs/' . 'small_' . $oldImage );
            unlink( './site_images/services/thumbs/' . 'thumb_' . $oldImage );
            unlink( './site_images/services/thumbs/' . 'big_' . $oldImage );
        }
        $mydb->redirect("./?page=services&msg=updated");
    } else {
        $mydb->redirect("./?page=services&msg=dberror");
    }

}

//Main Page
if (@$_GET['action']) {

    //Partners Delete

    if (@$_GET['action'] == 'delete') {
        $imageName = $mydb->select_field("image", "tbl_services", "id='".$id."'");
        $mydb->where("id", $id);
        $delete =$mydb->delete("tbl_services");

        if(@$delete) {
            unlink( './site_images/services/' . $imageName );
            unlink( './site_images/services/thumbs/' . 'small_' . $imageName );
            unlink( './site_images/services/thumbs/' . 'thumb_' . $imageName );
            unlink( './site_images/services/thumbs/' . 'big_' . $imageName );
            $mydb->redirect("./?page=services&msg=deleted");
        } else {
            $mydb->redirect("./?page=services&msg=deleted-error");
        }

    } elseif (@$_GET['action'] == 'edit') {

    //Partners Edit
?>

        <div class="content-wrapper">

            <section class="content-header">

                <h1>Services&nbsp;&nbsp;<a href="./?page=services">
                        <button class="btn btn-success" id="edit" title="Go Back"><i
                                class="fa fa-arrow-circle-left"></i> Go Back
                        </button>
                    </a>&nbsp;&nbsp;<a href="./?page=services-add">
                        <button class="btn btn-primary" title="Add Services"><i class="fa fa-plus"></i> Add New
                        </button>
                    </a></h1>

                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li class="active"><a href="#">Edit Page</a></li>
                </ol>

            </section>

            <section class="content">

                <form id="services-form" method="post" enctype="multipart/form-data" action="">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title" id='page-title'>Edit Services</h3>
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

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-briefcase"></i>  Title</span>
                                                    <input type="text" class="form-control" name="title"
                                                           placeholder="Services Title" 
                                                           value="<?php echo $mydb->select_field("title", "tbl_services", "id='" . $id . "'"); ?>"
                                                           required
                                                    />
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <?php $display = $mydb->select_field("display", "tbl_services", "id='" . $id . "'"); ?>
                                                <div class="input-group"><span class="input-group-addon"><input name="display"
                                                                                                                type="checkbox"
                                                                                                                <?php echo( $display == 1 ? 'checked':''); ?>
                                                                                                                value="1"/></span>
                                                    <input type="text" class="form-control" value="Display"
                                                           readonly/>
                                                </div>
                                            </div>

                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="input-group form-group"><span class="input-group-addon"><i
                                                            class="fa fa-file-picture-o"></i>  <?php echo $mydb->select_field("title", "tbl_services", "id='" . $id . "'"); ?></span>
                                                    <input class="btn btn-info btn-flat" name="image" type="file"/>
                                                </div>
                                            </div>

                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-newspaper-o"></i> Description </span>
                                                    <textarea class="ckeditor" name="content" id="editor1"
                                                              ><?php echo $mydb->select_field("content", "tbl_services", "id='" . $id . "'"); ?></textarea>
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
                            <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i>
                                Save changes
                            </button>
                            &nbsp;&nbsp;<a href="?page=services" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                    </div>
                </form>

            </section>

        </div>

    <?php }} else { ?>

    <!--Services List-->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Services&nbsp;&nbsp;<a href="./?page=services-add">
                    <button class="btn btn-primary" title="Add Services"><i class="fa fa-plus"></i> Add New</button>
                </a></h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Services</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Services List</h3>
                        </div>

                        <div class="box-body">

                            <?php
                            // Notifications

                            if (@$_GET['msg'] == 'added') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Added!
                                    </h4>
                                    Services has been Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Updated!
                                    </h4>
                                    Services has been Updated.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Deleted!
                                    </h4>
                                    Services has been Deleted.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'image_error_extension') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="fa fa-bell faa-ring animated"></i> Error!
                                    </h4>
                                    Only .JPG, .JPEG and .PNG images allowed.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'image_error_size') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="fa fa-bell faa-ring animated"></i> Error!
                                    </h4>
                                    Image size must be less than 2 MB.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'image_copy_error') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="fa fa-bell faa-ring animated"></i> Error!
                                    </h4>
                                    Image could not be saved.
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
                            <?php } elseif (@$_GET['msg'] == 'deleted-error') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
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
                                $mydb->orderByCols = array("created_at desc");
                                $result = $mydb->select("tbl_services");
                                $count = 1;
                                if ($mydb->totalRows > 0) {
                                    foreach ($result as $row):
                                ?>

                                        <tr>
                                            <td class="text-center"><?=$count; ?>.</td>
                                            <td><b><?=$row['title']; ?></b></td>
                                            <td class="text-center"><img
                                                    src="./site_images/services/thumbs/small_<?=$row['image']; ?>"
                                                    alt="no_image"/></td>
                                            <td class="text-center"><?=date("F d, Y", strtotime($row['created_at'])); ?>
                                                <br/>
                                                <small style="font-style:italic;">by <?=$row['created_by']; ?></small>
                                            </td>
                                            <td class="text-center"><?php echo($row['updated_at'] == '0000-00-00 00:00:00' ? '<small style="font-style:italic;">No Updates Made</small>' : date("F d, Y", strtotime($row["updated_at"])) . '<br /><small style="font-style:italic;">by ' . $row["updated_by"] . '</small>'); ?></td>
                                            <td class="text-center">
                                                <!--Edit-->
                                                <a href="./?page=services&action=edit&id=<?=$row['id']; ?>">
                                                    <button type="button" class="btn btn-success"><i
                                                            class="fa fa-edit"></i> Edit
                                                    </button>
                                                </a>&nbsp;&nbsp;
                                                <!--Delete-->
                                                <a href="#delete" data-toggle="modal" data-id="<?=$row['id']; ?>"
                                                   id="delete<?=$row['id']; ?>" class="btn btn-danger"
                                                   onClick="deleteBrands(<?=$row['id']; ?>)"><i
                                                        class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>

                                        <?php
                                        $count++;
                                    endforeach;
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center" style="color:#903;">No Services Listed</td>
                                    </tr>
                                <?php } ?>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="6"><?php echo C_NAME; ?> Services Table</th>
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
    <script type="text/javascript">
        function deleteBrands(id) {
            var conn = './?page=services&action=delete&id=' + id;
            $('#delete a').attr("href", conn);
        }

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

    <div id="delete" class="modal">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3>Delete Services</h3>
        </div>
        <div class="modal-body">
            <p>
                Do you want to delete Services from the List?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div>

<?php } ?>

