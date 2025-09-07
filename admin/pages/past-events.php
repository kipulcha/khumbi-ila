<?php

$id = @$_GET['id'];

if (isset($_POST['submit'])) {

    //image functions
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

            $newname = "site_images/past-events/" . $image_name;
            $copied = copy($_FILES['image']['tmp_name'], $newname);

            if (!$copied) {
                $mydb->redirect("./?page=team&msg=image_copy_error");
                $errors = 1;
                exit();
            } else {

                $thumb_name = 'site_images/past-events/thumbs/thumb_' . $image_name;

                $thumb = make_thumb($newname, $thumb_name, 600, 480);

                $thumb_name = 'site_images/past-events/thumbs/small_' . $image_name;

                $thumb = make_thumb($newname, $thumb_name, 100, 80);

            }
        }

        //update database with image
        $updateData = array("title" => ucwords($_POST['title']), "category" => $_POST['category'], "slug" => $slug, "display" => $_POST['display'], "image" => $image_name, "location" => $_POST['location'], "season" => $_POST['season'], "description" => $_POST['description'], "group_members" => $_POST['group_members'],"updated_at" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_past_events", $updateData);

    } else {

        //update database without image
        $updateData = array("title" => ucwords($_POST['title']), "category" => $_POST['category'], "display" => $_POST['display'], "slug" => $slug, "display" => $_POST['display'], "location" => $_POST['location'], "season" => $_POST['season'], "description" => $_POST['description'], "group_members" => $_POST['group_members'],"updated_at" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_past_events", $updateData);

    }
    
    if (@$update) {
        $mydb->redirect("./?page=past-events&msg=updated");
    } else {
        $mydb->redirect("./?page=past-events&msg=dberror");
    }

}

//Main Page
if (@$_GET['action']) {

    //Past Event Delete

    if (@$_GET['action'] == 'delete') {
        $mydb->where("id", $id);
        $delete =$mydb->delete("tbl_past_events");

        if(@$delete) {
            $mydb->redirect("./?page=past-events&msg=deleted");
        } else {
            $mydb->redirect("./?page=past-events&msg=deleted-error");
        }

    } elseif (@$_GET['action'] == 'edit') {

    //Past Event Edit
?>

        <div class="content-wrapper">

            <section class="content-header">

                <h1>Past Event&nbsp;&nbsp;<a href="./?page=past-events">
                        <button class="btn btn-success" id="edit" title="Go Back"><i
                                class="fa fa-arrow-circle-left"></i> Go Back
                        </button>
                    </a>&nbsp;&nbsp;<a href="./?page=past-events-add">
                        <button class="btn btn-primary" title="Add Past Event"><i class="fa fa-plus"></i> Add New
                        </button>
                    </a></h1>

                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Past Event</a></li>
                    <li class="active"><a href="#">Edit Page</a></li>
                </ol>

            </section>

            <section class="content">

                <form id="past-events-form" method="post" enctype="multipart/form-data" action="">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title" id='page-title'>Edit Past Event</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="input-group"><span class="input-group-addon"><i
                                                class="fa fa-user"></i>  Title</span>
                                        <input type="text" class="form-control" name="title"
                                               value="<?php echo $mydb->select_field("title", "tbl_past_events", "id='" . $id . "'"); ?>"
                                               placeholder="Past Events Title" required/>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <?php $current = $mydb->select_field("display", "tbl_past_events", "id='" . $id . "'"); ?>
                                    <div class="input-group"><span class="input-group-addon"><input
                                                name="display" type="checkbox"
                                                value="1" <?php echo($current == 1 ? 'checked="checked"' : ''); ?> /></span>
                                                <input type="text" class="form-control" value="Show Event"
                                               readonly/>
                                    </div>
                                </div>

                            </div>
                            <br>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="input-group form-group"><span class="input-group-addon"><i
                                                class="fa fa-file-picture-o"></i>  <?php echo $mydb->select_field("image", "tbl_past_events", "id='" . $id . "'"); ?></span>
                                        <input class="btn btn-info btn-flat" name="image" type="file"/>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-group"><span class="input-group-addon"><i
                                                class="fa fa-map-marker"></i>  Location</span>
                                        <input type="text" class="form-control" name="location"
                                               value="<?php echo $mydb->select_field("location", "tbl_past_events", "id='" . $id . "'"); ?>"
                                               placeholder="Past Events Location" required/>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="input-group"><span class="input-group-addon"><i
                                                class="fa fa-map-marker"></i> Season/Year</span>
                                        <input type="text" class="form-control" name="season"
                                               value="<?php echo $mydb->select_field("season", "tbl_past_events", "id='" . $id . "'"); ?>"
                                               placeholder="Past Events Season/Year" required/>
                                    </div>
                                </div>

                                <?php $current = $mydb->select_field("category", "tbl_past_events", "id='" . $id . "'"); ?>
                                <div class="col-lg-6">
                                    <div class="input-group"><span class="input-group-addon"><i
                                                class="fa fa-list"></i> Category</span>
                                        <select name="category" class="form-control" >
                                            <option value="0" <?php echo( $current == '0' ? 'selected':'' ); ?>>Expedition</option>
                                            <option value="1" <?php echo( $current == '1' ? 'selected':'' ); ?>>Trekking</option>
                                            <option value="2" <?php echo( $current == '2' ? 'selected':'' ); ?>>Peak Climbing</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <br>

                            <div class="input-group"><span class="input-group-addon"><i
                                        class="fa fa-newspaper-o"></i>  Description </span>
                                            <textarea class="ckeditor" name="description" id="editor1"
                                                      required><?php echo $mydb->select_field("description", "tbl_past_events", "id='" . $id . "'"); ?>
                                               </textarea>
                            </div>
                            <br>

                            <div class="input-group"><span class="input-group-addon"><i
                                        class="fa fa-users"></i> Group Members</span>
                                            <textarea class="ckeditor" name="group_members" id="editor1"
                                                      required><?php echo $mydb->select_field("group_members", "tbl_past_events", "id='" . $id . "'"); ?></textarea>
                            </div>


                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i>
                                Save changes
                            </button>
                            &nbsp;&nbsp;<a href="?page=past-events" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                    </div>
                </form>

            </section>

        </div>

    <?php }} else { ?>

    <!--Past Event List-->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Past Event&nbsp;&nbsp;<a href="./?page=past-events-add">
                    <button class="btn btn-primary" title="Add Past Event"><i class="fa fa-plus"></i> Add New</button>
                </a></h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Past Event</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Past Event List</h3>
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
                                    Past Event has been Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Updated!
                                    </h4>
                                    Past Event has been Updated.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Deleted!
                                    </h4>
                                    Past Event has been Deleted.
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
                                $result = $mydb->select("tbl_past_events");
                                $count = 1;
                                if ($mydb->totalRows > 0) {
                                    foreach ($result as $row):
                                        ?>

                                        <tr>
                                            <td class="text-center"><?=$count; ?>.</td>
                                            <td><b><?=$row['title']; ?></b></td>
                                            <td class="text-center"><img
                                                    src="./site_images/past-events/thumbs/small_<?=$row['image']; ?>"
                                                    alt="no_image"/></td>
                                            <td class="text-center"><?=date("F d, Y", strtotime($row['created_at'])); ?>
                                                <br/>
                                                <small style="font-style:italic;">by <?=$row['created_by']; ?></small>
                                            </td>
                                            <td class="text-center"><?php echo($row['updated_at'] == '0000-00-00 00:00:00' ? '<small style="font-style:italic;">No Updates Made</small>' : date("F d, Y", strtotime($row["updated_at"])) . '<br /><small style="font-style:italic;">by ' . $row["updated_by"] . '</small>'); ?></td>
                                            <td class="text-center">
                                                <!--Edit-->
                                                <a href="./?page=past-events&action=edit&id=<?=$row['id']; ?>">
                                                    <button type="button" class="btn btn-success"><i
                                                            class="fa fa-edit"></i> Edit
                                                    </button>
                                                </a>&nbsp;&nbsp;
                                                <!--Delete-->
                                                <a href="#delete" data-toggle="modal" data-id="<?=$row['id']; ?>"
                                                   id="delete<?=$row['id']; ?>" class="btn btn-danger"
                                                   onClick="delete-past-events(<?=$row['id']; ?>)"><i
                                                        class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>

                                        <?php
                                        $count++;
                                    endforeach;
                                } else { ?>

                                    <tr>
                                        <td colspan="6" class="text-center" style="color:#903;">No Events Listed</td>
                                    </tr>

                                <?php } ?>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="6"><?php echo C_NAME; ?> Past Events Table</th>
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
        function delete-past-events(id) {
            var conn = './?page=past-events&action=delete&id=' + id;
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
            <h3>Delete Past Event</h3>
        </div>
        <div class="modal-body">
            <p>
                Do you want to delete Past Event from the List?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div>

<?php } ?>

