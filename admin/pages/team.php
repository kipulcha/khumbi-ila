<?php

$id = @$_GET['id'];

if (isset($_POST['submit'])) {

//image functions
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

    $image = $_FILES['image']['name'];


    if ($image) {

        //image upload
        $filename = stripslashes($_FILES['image']['name']);

        $extension = getExtension($filename);
        $extension = strtolower($extension);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png")) {
            $mydb->redirect("./?page=team&msg=image_error_extension");
            $errors = 1;
            exit;

        } else {

            $image_name = date('ymd') . time() . '.' . $extension;

            $newname = "site_images/team/" . $image_name;
            $copied = copy($_FILES['image']['tmp_name'], $newname);

            if (!$copied) {
                $mydb->redirect("./?page=team&msg=image_copy_error");
                $errors = 1;
                exit();
            } else {

                $thumb_name = 'site_images/team/thumbs/thumb_' . $image_name;

                $thumb = make_thumb($newname, $thumb_name, 400, 320);

                $thumb_name = 'site_images/team/thumbs/small_' . $image_name;

                $thumb = make_thumb($newname, $thumb_name, 100, 80);

            }
        }

        //update database with image
        $oldImage = $mydb->select_field("image", "tbl_team", "id='" . $id . "'");
        $updateData = array("title" => $_POST['title'], "image" => $image_name,"current" => $_POST['current'],"post" => $_POST['post'],"description" => $_POST['description'],"updated" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_team", $updateData);

    } else {

        //update database without image
        $updateData = array("title" => $_POST['title'],"current" => $_POST['current'],"post" => $_POST['post'],"description" => $_POST['description'],"updated" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_team", $updateData);

    }

    if (@$update) {
        if(@$image_name){
            unlink( './site_images/team/' . $oldImage );
            unlink( './site_images/team/thumbs/' . 'small_' . $oldImage );
            unlink( './site_images/team/thumbs/' . 'thumb_' . $oldImage );
        }
        $mydb->redirect("./?page=team&msg=updated");
    } else {
        $mydb->redirect("./?page=team&msg=dberror");
    }

}

//Main Page
if (@$_GET['action']) {

    //Team Member Delete

    if (@$_GET['action'] == 'delete') {
        $imageName = $mydb->select_field("image", "tbl_team", "id='".$id."'");
        $mydb->where("id", $id);
        $delete =$mydb->delete("tbl_team");

        if(@$delete) {
            unlink( './site_images/team/' . $imageName );
            unlink( './site_images/team/thumbs/' . 'small_' . $imageName );
            unlink( './site_images/team/thumbs/' . 'thumb_' . $imageName );
            $mydb->redirect("./?page=team&msg=deleted");
        } else {
            $mydb->redirect("./?page=team&msg=deleted-error");
        }

    } elseif (@$_GET['action'] == 'edit') {

    //Team Member Edit
?>

        <div class="content-wrapper">

            <section class="content-header">

                <h1>Team Member&nbsp;&nbsp;<a href="./?page=team">
                        <button class="btn btn-success" id="edit" title="Go Back"><i
                                class="fa fa-arrow-circle-left"></i> Go Back
                        </button>
                    </a>&nbsp;&nbsp;<a href="./?page=team-add">
                        <button class="btn btn-primary" title="Add Team Member"><i class="fa fa-plus"></i> Add New
                        </button>
                    </a></h1>

                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Team Member</a></li>
                    <li class="active"><a href="#">Edit Page</a></li>
                </ol>

            </section>

            <section class="content">

                <form id="team-form" method="post" enctype="multipart/form-data" action="">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title" id='page-title'>Edit Team Member</h3>
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
                                                            class="fa fa-user"></i>  Full Name</span>
                                                    <input type="text" class="form-control" name="title"
                                                           placeholder="Team Member Name"
                                                           value="<?php echo $mydb->select_field("title", "tbl_team", "id='" . $id . "'"); ?>"
                                                           required/>
                                                </div>
                                            </div>

                                            <?php $current = $mydb->select_field("current", "tbl_team", "id='" . $id . "'"); ?>
                                            <div class="col-lg-6">
                                                <div class="input-group"><span class="input-group-addon"><input
                                                            name="current" type="checkbox"
                                                            value="1" <?php echo($current == 1 ? 'checked="checked"' : ''); ?> /></span>
                                                    <input type="text" class="form-control" value="Current Team Member"
                                                           readonly/>
                                                </div>
                                            </div>

                                        </div>
                                        <br>


                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="input-group form-group"><span class="input-group-addon"><i
                                                            class="fa fa-file-picture-o"></i> <?php echo $mydb->select_field("image", "tbl_team", "id='" . $id . "'"); ?></span>
                                                    <input class="btn btn-info btn-flat" name="image" type="file"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-user"></i>  Post</span>
                                                    <input type="text" class="form-control" name="post"
                                                           placeholder="Team Member Post"
                                                           value="<?php echo $mydb->select_field("post", "tbl_team", "id='" . $id . "'"); ?>"
                                                           required/>
                                                </div>
                                            </div>

                                        </div>
                                        <br>

                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-newspaper-o"></i> Description </span>
                                            <textarea class="ckeditor" name="description" id="editor1"
                                                      required><?php echo $mydb->select_field("description", "tbl_team", "id='" . $id . "'"); ?></textarea>
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
                            &nbsp;&nbsp;<a href="?page=team" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                    </div>
                </form>

            </section>

        </div>

    <?php }} else { ?>

    <!--Team Member List-->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Team Member&nbsp;&nbsp;<a href="./?page=team-add">
                    <button class="btn btn-primary" title="Add Team Member"><i class="fa fa-plus"></i> Add New</button>
                </a></h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Team Member</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Team Member List</h3>
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
                                    Team Member has been Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Updated!
                                    </h4>
                                    Team Member has been Updated.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Deleted!
                                    </h4>
                                    Team Member has been Deleted.
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
                                $result = $mydb->select("tbl_team");
                                $count = 1;
                                if ($mydb->totalRows > 0) {
                                    foreach ($result as $row):
                                        ?>

                                        <tr>
                                            <td class="text-center"><?=$count; ?>.</td>
                                            <td><b><?=$row['title']; ?></b><?php echo($row['current'] == '1' ? "&nbsp;&nbsp;<small class='label bg-green'>current team</small>" : ''); ?></td>
                                            <td class="text-center"><img
                                                    src="./site_images/team/thumbs/small_<?=$row['image']; ?>"
                                                    alt="no_image"/></td>
                                            <td class="text-center"><?=date("F d, Y", strtotime($row['inserted'])); ?>
                                                <br/>
                                                <small style="font-style:italic;">by <?=$row['inserted_by']; ?></small>
                                            </td>
                                            <td class="text-center"><?php echo($row['updated'] == '0000-00-00 00:00:00' ? '<small style="font-style:italic;">No Updates Made</small>' : date("F d, Y", strtotime($row["updated"])) . '<br /><small style="font-style:italic;">by ' . $row["updated_by"] . '</small>'); ?></td>
                                            <td class="text-center">
                                                <!--Edit-->
                                                <a href="./?page=team&action=edit&id=<?=$row['id']; ?>">
                                                    <button type="button" class="btn btn-success"><i
                                                            class="fa fa-edit"></i> Edit
                                                    </button>
                                                </a>&nbsp;&nbsp;
                                                <!--Delete-->
                                                <a href="#delete" data-toggle="modal" data-id="<?=$row['id']; ?>"
                                                   id="delete<?=$row['id']; ?>" class="btn btn-danger"
                                                   onClick="delete_team(<?=$row['id']; ?>)"><i
                                                        class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>

                                        <?php
                                        $count++;
                                    endforeach;
                                } else { ?>

                                    <tr>
                                        <td colspan="6" class="text-center" style="color:#903;">No Teams Listed</td>
                                    </tr>

                                <?php } ?>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="6"><?php echo C_NAME; ?> Contact Table</th>
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
        function delete_team(id) {
            var conn = './?page=team&action=delete&id=' + id;
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
            <h3>Delete Team Member</h3>
        </div>
        <div class="modal-body">
            <p>
                Do you want to delete Team Member from the List?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div>

<?php } ?>

