<style type="text/css">
    /* Sortable ******************/
    #sortable { 
        list-style: none; 
        text-align: left; 
    }
    #sortable li { 
        margin: 0 0 10px 0;
        /*height: 30px; */
        background: #ffffff;
        border: 1px solid #999999;
        border-radius: 5px;
        color: #333333;
    }
    #sortable li span {
        background-color: #b4b3b3;
        background-image: url('./dist/img/drag.png');
        background-repeat: no-repeat;
        background-position: center;
        width: 40px;
        height: 36px; 
        display: inline-block;
        float: left;
        cursor: move;
    }
        /*#sortable li img {
            height: 65px;
            border: 5px solid #cccccc;
            display: inline-block;
            float: left;
        }*/
        #sortable li div {
            padding: 2px;
            text-align: center;
        }
        #sortable li h2 {    
            font-size: 12px;
            line-height: 2px;
        }
    </style>
    <?php

    $id = @$_GET['id'];

/**
 ** Update Script
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

    $programs = json_encode($programs);

    $errors = 0;

    $image = $_FILES['image']['name'];

    //image upload
    if ($image) {

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

        $oldImage = $mydb->select_field("image", "tbl_packages", "id='" . $id . "'");
        //update database with image
        $updateData = array("title" => $title, "slug" => $slug, "sub_title" => $sub_title, "price" => $price, "programs" => $programs, "image" => $image_name, "duration" => $duration, "dates" => $dates, "grade" => $grade, "youtube_id" => $youtube_id, "featured" => $featured, "prices" => $prices, "information" => $information, "overview" => $overview, "itinerary" => $itinerary, "updated_at" => $currentDate, "updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_packages", $updateData);

    } else {

        //update database without image
        $updateData = array("title" => $title, "slug" => $slug, "sub_title" => $sub_title, "price" => $price, "programs" => $programs, "duration" => $duration, "dates" => $dates, "grade" => $grade, "youtube_id" => $youtube_id, "featured" => $featured, "prices" => $prices, "information" => $information, "overview" => $overview, "itinerary" => $itinerary, "updated_at" => $currentDate, "updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_packages", $updateData);

    }

    if (@$update) {
        if(@$image_name){
            unlink( './site_images/packages/' . $oldImage );
            unlink( './site_images/packages/thumbs/' . 'small_' . $oldImage );
            unlink( './site_images/packages/thumbs/' . 'thumb_' . $oldImage );
        }
        $mydb->redirect("./?page=packages&msg=updated");
    } else {
        $mydb->redirect("./?page=packages&msg=dberror");
    }

}

if (@$_GET['action']) {

/**
 ** Package Delete Action
 **/
if (@$_GET['action'] == 'delete') {
    $imageName = $mydb->select_field("image", "tbl_packages", "id='".$id."'");

    $mydb->where("id", $id);
    $delete = $mydb->delete("tbl_packages");

    if (@$delete) {
        unlink( './site_images/packages/' . $imageName );
        unlink( './site_images/packages/thumbs/' . 'small_' . $imageName );
        unlink( './site_images/packages/thumbs/' . 'thumb_' . $imageName );

        $mydb->redirect("./?page=packages&msg=deleted");
    } else {
        $mydb->redirect("./?page=packages&msg=deleted-error");
    }
}

/**
** Package EDIT HTML Starts
**/
elseif (@$_GET['action'] == 'edit') {
    ?>

    <div class="content-wrapper">

        <section class="content-header">

            <h1>Packages&nbsp;&nbsp;<a href="./?page=packages">
                <button class="btn btn-success" id="edit" title="Go Back"><i
                    class="fa fa-arrow-circle-left"></i> Go Back
                </button>
            </a>&nbsp;&nbsp;<a href="./?page=packages-add">
            <button class="btn btn-primary" title="Add Packages"><i class="fa fa-plus"></i> Add New
            </button>
        </a></h1>

        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Packages</a></li>
            <li class="active"><a href="#">Edit Page</a></li>
        </ol>

    </section>

    <section class="content">

        <form id="packages-form" method="post" enctype="multipart/form-data" action="">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Edit Packages</h3>
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
                                            class="fa fa-bullhorn"></i>  Title</span>
                                            <input type="text" class="form-control" name="title"
                                            placeholder="Packages Title" value="<?php echo $mydb->select_field("title", "tbl_packages", "id='" . $id . "'"); ?>" required/>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <?php $checked = $mydb->select_field("featured", "tbl_packages", "id='" . $id . "'"); ?>
                                        <div class="input-group"><span class="input-group-addon"><input name="featured"
                                            <?php echo ($checked == 1 ? 'checked':''); ?>
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
                                            placeholder="Packages Sub Title" value="<?php echo $mydb->select_field("sub_title", "tbl_packages", "id='" . $id . "'"); ?>"/>
                                        </div>
                                    </div>                                           

                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-bookmark"></i>  Grade</span>
                                            <input type="text" class="form-control" name="grade"
                                            placeholder="Grade" value="<?php echo $mydb->select_field("grade", "tbl_packages", "id='" . $id . "'"); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-clock-o"></i> Duration</span>
                                            <input type="text" class="form-control" name="duration"
                                            placeholder="Duration in Days" value="<?php echo $mydb->select_field("duration", "tbl_packages", "id='" . $id . "'"); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-usd"></i>  Price</span>
                                            <input type="text" class="form-control" name="price"
                                            placeholder="Price" value="<?php echo $mydb->select_field("price", "tbl_packages", "id='" . $id . "'"); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-calendar"></i> Package Dates:</span>
                                            <input type="text" class="form-control" value="<?php echo $mydb->select_field("dates", "tbl_packages", "id='" . $id . "'"); ?>" name="dates"
                                            placeholder="Package Dates" />
                                        </div>
                                        <p><small><b>Note:</b> Please use comma(,) for separating dates.</small></p>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input-group form-group"><span class="input-group-addon"><i
                                            class="fa fa-file-picture-o"></i> <?php echo $mydb->select_field("image", "tbl_packages", "id='" . $id . "'"); ?></span>
                                            <input class="btn btn-info btn-flat" name="image" type="file"/>
                                        </div>
                                        <p><small><b>Note:</b> 800x800px image recommended.</small></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-youtube"></i> Youtube ID</span>
                                            <input type="text" class="form-control" name="youtube_id"
                                            placeholder="Youtube URL ID" value="<?php echo $mydb->select_field("youtube_id", "tbl_packages", "id='" . $id . "'"); ?>" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <?php
                                        $selectedPrograms = json_decode($mydb->select_field("programs", "tbl_packages", "id='" . $id . "'"));
                                        ?>
                                        <select class="form-control select2" name="programs[]" multiple="multiple" data-placeholder="Select programs" style="width: 100%;" required>
                                            <?php
                                            $mydb->where("child","0");
                                            $result = $mydb->select("tbl_programs");
                                            if ($mydb->totalRows > 0) {
                                                foreach ($result as $row):
                                                    ?>
                                                <option
                                                value="<?= $row['id']; ?>"
                                                <?php
                                                if (in_array($row['id'], $selectedPrograms)) {
                                                    echo "selected";
                                                }
                                                ?>
                                                ><?= $row['title']; ?></option>
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
                                    ><?php echo $mydb->select_field("overview", "tbl_packages", "id='" . $id . "'"); ?></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <textarea class="ckeditor" name="itinerary" id="editor1"
                                    ><?php echo $mydb->select_field("itinerary", "tbl_packages", "id='" . $id . "'"); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <textarea class="ckeditor" name="prices" id="editor1"
                                    ><?php echo $mydb->select_field("prices", "tbl_packages", "id='" . $id . "'"); ?></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <textarea class="ckeditor" name="information" id="editor1"
                                    ><?php echo $mydb->select_field("information", "tbl_packages", "id='" . $id . "'"); ?></textarea>
                                </div>
                            </div>

                        </div>
                        <br>

                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <!-- .box-footer -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i>
                    Save changes
                </button>
                &nbsp;&nbsp;<a href="?page=packages" class="btn btn-danger"><i class="fa fa-remove"></i>
                Cancel</a>
            </div>
            <!-- /.box-footer -->

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

    <?php } ?>

<!--==========================================================
    Main Page Starts Here
    ===========================================================-->
    <?php } else { ?>
    <div class="content-wrapper">

        <section class="content-header">
            <h1>Packages&nbsp;&nbsp;<a href="./?page=packages-add">
                <button class="btn btn-primary" title="Add Packages"><i class="fa fa-plus"></i> Add New</button>
            </a></h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Packages</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-6 col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Packages List</h3>
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
                                    Packages has been Added.
                                </div>
                                <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                        class="fa fa-remove"></i></button>
                                        <h4>
                                            <i class="icon fa fa-check"></i>
                                            Succesfully Updated!
                                        </h4>
                                        Packages has been Updated.
                                    </div>
                                    <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                    <div class="alert alert-info alert-dismissable">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                            <h4>
                                                <i class="icon fa fa-check"></i>
                                                Succesfully Deleted!
                                            </h4>
                                            Packages has been Deleted.
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
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>

                                                                    <?php
                                                                    $mydb->orderByCols = array("item_order");
                                                                    $result = $mydb->select("tbl_packages");
                                                                    $count = 1;
                                                                    if ($mydb->totalRows > 0) {
                                                                        foreach ($result as $row):
                                                                            ?>

                                                                        <tr>
                                                                            <td class="text-center"><?= $count; ?>.</td>
                                                                            <td><?php echo($row['featured'] == '1' ? "<i class='fa fa-star' style='color:#D3AF37'></i>&nbsp;&nbsp;" : ''); ?>
                                                                                <b><?= $row['title']; ?></b></td>
                                                                                <td class="text-center"><img
                                                                                    src="./site_images/packages/thumbs/small_<?= $row['image']; ?>"
                                                                                    alt="no_image"/></td>
                                                                                    <td class="text-center">
                                                                                        <!--Gallery-->
                                                                                        <a href="./?page=package-gallery&tag=packages&id=<?= $row['id']; ?>">
                                                                                            <button type="button" class="btn btn-success btn-xs"><i
                                                                                                class="fa fa-picture-o"></i> Gallery
                                                                                            </button>
                                                                                        </a>&nbsp;&nbsp;
                                                                                        <!--Edit-->
                                                                                        <a href="./?page=packages&action=edit&id=<?= $row['id']; ?>">
                                                                                            <button type="button" class="btn btn-success btn-xs"><i
                                                                                                class="fa fa-edit"></i> Edit
                                                                                            </button>
                                                                                        </a>&nbsp;&nbsp;
                                                                                        <!--Delete-->
                                                                                        <a href="#delete" data-toggle="modal" data-id="<?= $row['id']; ?>"
                                                                                           id="delete<?= $row['id']; ?>" class="btn btn-danger btn-xs"
                                                                                           onClick="delete_packages(<?= $row['id']; ?>)"><i
                                                                                           class="fa fa-trash"></i> Delete</a>
                                                                                       </td>
                                                                                   </tr>

                                                                                   <?php
                                                                                   $count++;
                                                                                   endforeach;
                                                                               } else { ?>

                                                                               <tr>
                                                                                <td colspan="4" class="text-center" style="color:#903;">No Packages Listed</td>
                                                                            </tr>

                                                                            <?php } ?>

                                                                        </tbody>

                                                                        <tfoot>
                                                                            <tr>
                                                                                <th class="text-center" colspan="4"><?php echo C_NAME; ?> Packages Table</th>
                                                                            </tr>
                                                                        </tfoot>

                                                                    </table>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-6 col-xs-12">
                                                           <div class="box box-danger">
                                                            <div class="box-header">
                                                                <h3 class="box-title">Sorting</h3>
                                                            </div>

                                                            <div class="box-body">
                                                                <ul id="sortable">
                                                                    <?php
                                                                    if ($mydb->totalRows > 0) {
                                                                        foreach ($result as $row):
                                                                            ?>
                                                                        <li id="<?php echo $row['id']; ?>">
                                                                            <span></span>
                                                                            <div><h2><?=$row['title']; ?></h2></div>
                                                                        </li>
                                                                        <?php
                                                                        endforeach;
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </section>
                                        </div>

    <!--==========================================================
        Delete/Data tables Script
        ===========================================================-->
        <script type="text/javascript">
            function delete_packages(id) {
                var conn = './?page=packages&action=delete&id=' + id;
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

        <!-- Sorting Start -->
        <script type="text/javascript" src="dist/js/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $('#sortable').sortable({
                    axis: 'y',
                    opacity: 0.7,
                    handle: 'span',
                    update: function(event, ui) {
                        var list_sortable = $(this).sortable('toArray').toString();
                            // change order in the database using Ajax
                            /*swal('Success!', 'Product Sorting Successfully', 'success')*/
                            /*alert(list_sortable)*/
                            $.ajax({
                                url: './pages/set_order.php',
                                type: 'POST',
                                data: {list_order:list_sortable},
                                beforeSend: function(){                

                                },
                                success : function(response)
                                {
                                  console.log("success");
                                  console.log("response "+response);
                                  var obj = jQuery.parseJSON( response);
                                  if (obj.status=='success') {
                                    swal('Success!', 'Product Sorting Successfully', 'success')
                                };

                            }
                        });
                        }   
                }); // fin sortable
            });
        </script>


        <!-- Sorting Ends -->

    <!--==========================================================
        Delete Modal
        ===========================================================-->
        <div id="delete" class="modal">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">&times;</button>
                <h3>Delete Packages</h3>
            </div>
            <div class="modal-body">
                <p>
                    Do you want to delete Packages from the List?
                </p>
            </div>
            <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                class="btn btn-danger" href="#">Cancel</a>
            </div>
        </div>

        <?php } ?>

