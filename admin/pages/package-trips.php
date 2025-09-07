<?php

$id = @$_GET['pid'];
$tag = @$_GET['tag'];

switch($tag){
    case 'expeditions':
        $program = 'tbl_expeditions';
        break;
    case 'trekking':
        $program = 'tbl_trekkings';
        break;
    case 'tours':
        $program = 'tbl_tours';
        break;
    default:
        $program = null;
}

if (isset($_POST['submit'])) {

    $images_dir = "site_images/packages-gallery";
    $result_final = "";
    $counter = 0;

    $known_photo_types = array(
        'image/pjpeg' => 'jpg',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/x-png' => 'png',
        'image/png' => 'png'
    );

    $gd_function_suffix = array(
        'image/pjpeg' => 'JPEG',
        'image/jpeg' => 'JPEG',
        'image/gif' => 'GIF',
        'image/bmp' => 'WBMP',
        'image/x-png' => 'PNG',
        'image/png' => 'PNG'
    );


    $photos_uploaded = $_FILES['photo_filename'];

    while ($counter <= count($photos_uploaded)) {
        require_once './lib/image-thumbs-crop.php';
        if (@$photos_uploaded['size'][$counter] > 0) {
            if (!array_key_exists($photos_uploaded['type'][$counter], $known_photo_types)) {
                $result_final .= "File " . ($counter + 1) . " is not a photo<br />";
            } else {
                //insert data
                $insertData = array("filename" => "0", "p_id" => $id, "caption" => $_POST['photo_caption'], "created_at" => $currentDate, "created_by" => $_SESSION['username']);
                $insert = $mydb->insert("tbl_trip_gallery", $insertData);

                $new_id = $mydb->lastInsertId;
                $filetype = $photos_uploaded['type'][$counter];
                $extention = $known_photo_types[$filetype];
                /*echo $extention;
                exit;*/
                $filename = $new_id . "." . $extention;

                $updateData = array("filename" => addslashes($filename));
                $mydb->where("id", $new_id);
                $update = $mydb->update("tbl_trip_gallery", $updateData);

                // Store the orignal file
                copy($photos_uploaded['tmp_name'][$counter], $images_dir . "/" . $filename);
                
                // Now we resize it
                resize_crop_image(400, 400, "site_images/packages-gallery/". $filename, "site_images/packages-gallery/thumb_". $filename, $extention);
            }
        }
        $counter++;
    }

    $mydb->redirect("./?page=package-gallery&tag=" .$tag. "&id=" . $id . "&msg=added");

} ?>


<div class="content-wrapper">
    <section class="content-header">
        <h1><?=ucwords($tag);?> Gallery&nbsp;&nbsp;<a href="./?page=package-gallery&tag=<?=$tag;?>&id=<?=$id;?>">
                <button class="btn btn-success" title="Image Gallery"><i class="fa fa-eye"></i> View Gallery</button>
            </a>
        </h1>

        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><?=ucwords($tag);?></li>
            <li class="active">Image Gallery</li>
        </ol>
    </section>

    <section class="content">
        <form enctype='multipart/form-data' action='' method='post' name='upload_form'>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Add Images</h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="input-group form-group"><span class="input-group-addon"> <i
                                                    class="fa fa-file-picture-o"></i> Select Photos</span>
                                            <input class="btn btn-info btn-flat" name='photo_filename[]' type="file"
                                                   multiple required/>
                                        </div>

                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-file-picture"></i> Photo Caption</span>
                                            <input type="text" class="form-control" name="photo_caption"
                                                   placeholder="Photo Caption"/
                                        </div>
                                    </div>
                                    <p style="color:#06F;"><b>Note</b>: Please upload upto 3 images at a time for convenience</p>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add
                                Photos
                            </button>
                            &nbsp;&nbsp;<a href="./?page=package-gallery&tag=<?=$tag;?>&id=<?= $id; ?>" class="btn btn-danger"><i
                                    class="fa fa-remove"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>