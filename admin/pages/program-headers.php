<!--=================================================================
This Script Performs CURD Operations for "N" number of categories.
=================================================================-->
<?php
$id = @$_GET['id'];

//Building Tree View
//$mydb->columns = array("id", "title", "parent_id", "image", "child");
$result = $mydb->select("tbl_programs");

$tree = buildTree($result);

//Build Tree Function
function buildTree(Array $data, $parent = 0)
{
    $tree = array();
    foreach ($data as $d) {
        if ($d['parent_id'] == $parent) {
            $children = buildTree($data, $d['id']);
            // set a trivial key
            if (!empty($children)) {
                $d['_children'] = $children;
            }
            $tree[] = $d;
        }
    }
    return $tree;
}

//For Sorted SELECT Dropdown View
function printTree($tree, $r = 0, $p = null)
{
    foreach ($tree as $i => $t) {
        $dash = ($t['parent_id'] == 0) ? '' : str_repeat(" → ", $r) . ' ';
        printf("\t<option value='%d'>%s%s</option>\n", $t['id'], $dash, $t['title']);
        if (isset($t['_children'])) {
            printTree($t['_children'], $r + 1, $t['parent_id']);
        }
    }
}

//For Sorted Table View, using IF condition for  Parent and Child
function printTreeTable($tree, $r = 0, $p = null)
{
    $count = 1;
    foreach ($tree as $i => $t) {
        if( $t['child'] == 0) {
            $deleteBtn = "<a href='#delete' data-toggle='modal' id='delete". $t['id'] . "' class='btn btn-danger btn-xs' onClick='delete_offer(" . $t['id'] . ")'><i class='fa fa-trash'></i> Delete</a>";
        } else {
            $deleteBtn = "<button type='button' class='disabled btn btn-xs'><i class='fa fa-lock'></i> Locked</button>";
        }

        if($r != 0) {
            $hierarchy = '';
            for($j =1; $j<=$r; $j++){
                $hierarchy .= '<i class=\'fa fa-angle-right\'></i>';
            }
            $count = '';
            $row = "<tr>
            <td class=\"text-center\">".$count."</td>
            <td>".$hierarchy."&nbsp;".$t['title']."</td>
            <td><img src='./site_images/programs/thumbs/small_".$t['image']."' style='margin:0 auto;' class='img-responsive' /></td>
            <td class='text-center'>
                <!--Edit-->
                <a href='./?page=program-headers&action=edit&id=".$t['id']."'><button type='button' class='btn btn-success btn-xs'><i class='fa fa-edit'></i> Edit</button></a>&nbsp;&nbsp;
                <!--Delete-->
                ".$deleteBtn."
            </td>
            </tr>";
        } else {
            $row = "<tr>
            <td class=\"text-center\">".$count.".</td>
            <td><b>".$t['title']."</b></td>
            <td><img src='./site_images/programs/thumbs/small_".$t['image']."' style='margin:0 auto;' class='img-responsive' /></td>
            <td class='text-center'>
                <!--Edit-->
                <a href='./?page=program-headers&action=edit&id=".$t['id']."'><button type='button' class='btn btn-success btn-xs'><i class='fa fa-edit'></i> Edit</button></a>&nbsp;&nbsp;
                <!--Delete-->
                ".$deleteBtn."                 
            </td>
            </tr>";
        }

        printf($row);
        if (isset($t['_children'])) {
            printTreeTable($t['_children'], $r + 1, $t['parent_id']);
        }
        $count++;
    }
}

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

/**
 ** Insert Script
 **/
if (isset($_POST['submit'])) {

    extract($_POST);

    //Slug
    require_once './pages/slug.php';

    $slug = slug($title, 'tbl_programs', '');

    //Image Upload
    $filename = stripslashes($_FILES['image']['name']);

    $extension = getExtension($filename);
    $extension = strtolower($extension);

    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
        $mydb->redirect("./?page=program-headers&msg=image_error_extension");
    } else {
        $image_name = $slug . time() . '.' . $extension;
        $newname = "site_images/programs/" . $image_name;
        $copied = copy($_FILES['image']['tmp_name'], $newname);

        //resize slider image
        resize_crop_image(800, 500, "site_images/programs/" . $image_name, "site_images/programs/thumbs/thumb_" . $image_name);
        resize_crop_image(786, 680, "site_images/programs/" . $image_name, "site_images/programs/thumbs/big_" . $image_name);
        resize_crop_image(102, 38, "site_images/programs/" . $image_name, "site_images/programs/thumbs/small_" . $image_name);
    }

    if (!(@$display)) {
        $display = '0';
    }

    if (!(@$type)) {
        $type = '';
    }

    $insertData = array("title" => $title, "parent_id" => $parent, "slug" => $slug, "type" => $type , "image" => $image_name, "display" => $display, "content" => $content, "created_at" => $currentDate, "created_by" => $_SESSION['username'], "updated_at" => '', "updated_by" => '');
    $insert = $mydb->insert("tbl_programs", $insertData);

    if (@$insert) {
        if($parent != '0'){
            $updateData = array("child" => 1,"updated_at" => $currentDate,"updated_by" => $_SESSION['username']);
            $mydb->where("id", $parent);
            $update = $mydb->update("tbl_programs", $updateData);
        }
       $mydb->redirect("./?page=program-headers&msg=added");
    } else {
        $mydb->redirect("./?page=program-headers&msg=dberror");
    }
}

/**
 ** Update Script
 **/
if (isset($_POST['submitEdit'])) {

    extract($_POST);

    //Slug
    require_once './pages/slug.php';
    $slug = slug($titleEdit, 'tbl_programs', '');

    if (!@(isset($displayEdit))) {
        $displayEdit = '0';
    }

    if (!(@$typeEdit)) {
        $typeEdit = $mydb->select_field("type", "tbl_programs", "id='" . $id . "'");
    }

    $image = $_FILES['imageEdit']['name'];

    if ($image) {

        $filename = stripslashes($_FILES['imageEdit']['name']);

        $extension = getExtension($filename);
        $extension = strtolower($extension);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            $mydb->redirect("./?page=program-headers&msg=image_error_extension");
        } else {
            $image_name = $slug . time() . '.' . $extension;
            $newname = "site_images/programs/" . $image_name;
            $copied = copy($_FILES['imageEdit']['tmp_name'], $newname);

            //resize slider image
            resize_crop_image(800, 500, "site_images/programs/" . $image_name, "site_images/programs/thumbs/thumb_" . $image_name);
            resize_crop_image(786, 680, "site_images/programs/" . $image_name, "site_images/programs/thumbs/big_" . $image_name);
            resize_crop_image(102, 38, "site_images/programs/" . $image_name, "site_images/programs/thumbs/small_" . $image_name);
        }

        $oldImage = $mydb->select_field("image", "tbl_programs", "id='" . $id . "'");
        //update database with image
        $updateData = array("title" => $titleEdit, "image" => $image_name, "display" => $displayEdit, "type" => $typeEdit, "content" => $contentEdit,"updated_at" => $currentDate,"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_programs", $updateData);
    } else {
        //update database without image
        $updateData = array("title" => $titleEdit, "display" => $displayEdit, "content" => $contentEdit,"updated_at" => $currentDate,"updated_by" => $_SESSION['username']);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_programs", $updateData);
    }

    if (@$update) {
        if(@$image_name){
            unlink( './site_images/programs/' . $oldImage );
            unlink( './site_images/programs/thumbs/' . 'small_' . $oldImage );
            unlink( './site_images/programs/thumbs/' . 'big_' . $oldImage );
            unlink( './site_images/programs/thumbs/' . 'thumb_' . $oldImage );
        }
        $mydb->redirect("./?page=program-headers&msg=updated");
    } else {
        $mydb->redirect("./?page=program-headers&msg=dberror");
    }
}
?>


<!--==========================================================
    Program Edit/Delete Page Starts Here
===========================================================-->

<?php if (@$_GET['action']) {

    /**
     ** Program Delete Start
     **/
    if (@$_GET['action'] == 'delete') {
        $imageName = $mydb->select_field("image", "tbl_programs", "id='".$id."'");
        $parentID = $mydb->select_field("parent_id", "tbl_programs", "id='".$id."'");

        $mydb->where("id", $id);
        $delete =$mydb->delete("tbl_programs");

        if(@$delete) {
            unlink( './site_images/programs/' . $imageName );
            unlink( './site_images/programs/thumbs/' . 'small_' . $imageName );
            unlink( './site_images/programs/thumbs/' . 'big_' . $imageName );
            unlink( './site_images/programs/thumbs/' . 'thumb_' . $imageName );

            $mydb->where("parent_id",$parentID);
            $result = $mydb->select("tbl_programs");

            if ($mydb->totalRows < 1) {
                $updateData = array("child" => '0',"updated_at" => $currentDate,"updated_by" => $_SESSION['username']);
                $mydb->where("id", $parentID);
                $update = $mydb->update("tbl_programs", $updateData);
            }

            $mydb->redirect("./?page=program-headers&msg=deleted");
        } else {
            $mydb->redirect("./?page=program-headers&msg=deleted-error");
        }
    }

    /**
     ** Program Edit Start
     **/
    if (@$_GET['action'] == 'edit') {
        ?>
        <div class="content-wrapper">

            <section class="content-header">

                <h1>Programs&nbsp;&nbsp;<a href="./?page=program-headers">
                        <button class="btn btn-success" id="edit" title="Go Back"><i
                                class="fa fa-arrow-circle-left"></i> Go Back
                        </button>
                    </a>
                </h1>

                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Programs</a></li>
                    <li class="active"><a href="#">Edit Page</a></li>
                </ol>

            </section>

            <section class="content">


                <form id="program-headers-form" method="post" enctype="multipart/form-data" action="">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title" id='page-title'>Edit Programs</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-rebel"></i> Program Title</span>
                                                    <input type="text" class="form-control" name="titleEdit"
                                                           placeholder="Program Title"
                                                           value="<?php echo $mydb->select_field("title", "tbl_programs", "id='" . $id . "'"); ?>"
                                                           required/>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <?php $checked = $mydb->select_field("display", "tbl_programs", "id='" . $id . "'"); ?>
                                                <div class="input-group"><span class="input-group-addon"><input name="displayEdit"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                <?php echo($checked == 1 ? 'checked':'');?> /></span>
                                                    <input type="text" class="form-control" value="Display"
                                                           readonly/>
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <?php
                                            $programType = $mydb->select_field("type", "tbl_programs", "id='" . $id . "'");
                                            if($programType):
                                            ?>
                                            <div class="col-lg-6">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                            class="fa fa-rebel"></i> Parent Program</span>
                                                    <select class="form-control" name="typeEdit" >
                                                        <option value="Tours">Tours</option>
                                                        <option value="Trip Extension">Trip Extension</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <div class="col-lg-6">
                                                <div class="input-group form-group"><span class="input-group-addon"><i
                                                            class="fa fa-file-picture-o"></i>  <?php echo $mydb->select_field("image", "tbl_programs", "id='" . $id . "'"); ?></span>
                                                    <input class="btn btn-info btn-flat" name="imageEdit" type="file"/>
                                                </div>
                                                <p><small><b>Note: </b>Use standard 800px×500px For Best Fit</small></p>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="input-group">
                                                    <textarea class="ckeditor" name="contentEdit"><?php echo $mydb->select_field("content", "tbl_programs", "id='" . $id . "'"); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary" name="submitEdit"><i
                                        class="fa fa-floppy-o"></i> Save changes
                                </button>
                                &nbsp;&nbsp;<a href="?page=program-headers" class="btn btn-danger"><i class="fa fa-remove"></i>
                                    Cancel</a>
                            </div>
                        </div>
                </form>

            </section>

        </div>

    <?php }
    //ProgramEdit Ends

} else { ?>

    <!--==========================================================
        Program Main Page Starts Here
        ===========================================================-->
    <div class="content-wrapper">

        <!--==========================================================
            Sub-Header
            ===========================================================-->
        <section class="content-header">
            <h1>Programs&nbsp;&nbsp;<a href="./?page=home">
                    <button class="btn btn-success" id="edit" title="Go Back"><i class="fa fa-arrow-circle-left"></i>
                        Back
                    </button>
                </a></h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Programs</li>
            </ol>
        </section>

        <!--==========================================================
        Table List View/Add Program
        ===========================================================-->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Programs List</h3>
                        </div><!-- /.box-header -->

                        <!--
                         Notifications Starts
                        -->
                        <div class="row" style="padding: 20px;">
                            <div class="col-md-12">
                                <?php if (@$_GET['msg'] == 'added') { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                                class="fa fa-remove"></i></button>
                                        <h4>
                                            <i class="icon fa fa-check"></i>
                                            Succesfully Added!
                                        </h4>
                                        Programhas been Added.
                                    </div>
                                <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                                class="fa fa-remove"></i></button>
                                        <h4>
                                            <i class="icon fa fa-check"></i>
                                            Succesfully Updated!
                                        </h4>
                                        Programs been Updated.
                                    </div>
                                <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                    <div class="alert alert-info alert-dismissable">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                                class="fa fa-remove"></i></button>
                                        <h4>
                                            <i class="icon fa fa-check"></i>
                                            Succesfully Deleted!
                                        </h4>
                                        Programhas been Deleted.
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
                                    <div class="alert alert-info alert-dismissable">
                                        <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                                class="fa fa-remove"></i></button>
                                        <h4>
                                            <i class="icon fa fa-check"></i>
                                            Error!
                                        </h4>
                                        Data could not be added.
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!--
                         Notifications Ends
                        -->

                        <!--==========================================================
                            Table List View
                            ===========================================================-->
                        <div style="padding: 5px;">
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
                                //Call Print Table Function
                                printTreeTable($tree);
                                ?>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="4"><?php echo C_NAME; ?> Program Table</th>
                                </tr>
                                </tfoot>

                            </table>
                        </div>
                        <!--
                         Table List Ends
                        -->

                    </div>
                </div>

                <!--==========================================================
                Add Programs
                ===========================================================-->
                <div class="col-md-6">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Program Add</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="program-form" method="post" enctype="multipart/form-data" action="">

                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="box-body">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-rebel"></i> Program Title</span>
                                                        <input type="text" class="form-control" name="title"
                                                               placeholder="Program Title" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-rebel"></i> Parent Program</span>
                                                        <?php
                                                        print('<select class="form-control" id="typeChange" name="parent">\n');
                                                        print('<option value="0" selected >None</option>');
                                                        printTree($tree);
                                                        print("</select>");
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row" id="typeDiv">
                                                <div class="col-lg-12">
                                                    <div class="input-group"><span class="input-group-addon"><i
                                                                class="fa fa-rebel"></i> Type</span>
                                                        <select class="form-control" name="type" id="type">
                                                            <option value="Tours">Tours</option>
                                                            <option value="Trip Extension">Trip Extension</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group"><span class="input-group-addon"><input name="display"
                                                                                                                    type="checkbox"
                                                                                                                    value="1"/></span>
                                                        <input type="text" class="form-control" value="Display"
                                                               readonly/>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>


                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group form-group"><span class="input-group-addon"><i
                                                                class="fa fa-file-picture-o"></i>  Image</span>
                                                        <input class="btn btn-info btn-flat" name="image" type="file" required/>
                                                    </div>
                                                    <p><small><b>Note: </b>Use standard 800px×500px For Best Fit</small></p>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group">
                                                        <textarea class="ckeditor" name="content">Content Here ...</textarea>
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
                                <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i>
                                    Add Program
                                </button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box -->
            </div>
            <!--
             Program Add Ends
            -->
            
        </section>
    </div>

    <!--==========================================================
    Delete Script
    ===========================================================-->
    <script>
        function delete_offer(id) {
            var conn = './?page=program-headers&action=delete&id=' + id;
            $('#delete a').attr("href", conn);
        }
    </script>

    <!--==========================================================
    Delete Modal
    ===========================================================-->
    <div id="delete" class="modal">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3>Delete Program</h3>
        </div>
        <div class="modal-body">
            <p>
                Do you want to Delete Program?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div>

    <!--==========================================================
    Show/Hide Type, Search and Pagination
    ===========================================================-->
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

        $('#typeChange').change(function (){
            var id = $('#typeChange').children(":selected").attr("value");
            if( id != '0' ){
                $('#type').val('');
                $('#typeDiv').hide();
            } else {
                $('#typeDiv').show();
            }
        });

    </script>

<?php } ?>
