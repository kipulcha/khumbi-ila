<style>
  .polaroid {
    width: 100%;
  }

  .polaroid img {
    border: 10px solid #fff;
    border-bottom: 45px solid #fff;
    -webkit-box-shadow: 3px 3px 3px #777;
    -moz-box-shadow: 3px 3px 3px #777;
    box-shadow: 3px 3px 3px #777;
  }

  .polaroid p {
    position: absolute;
    text-align: left;
    width: 100%;
    bottom: 0px;
    padding: 2px;
    font: 400 18px/1 ;
    color: #1d1d1d;
  }

</style>
<?php 
if (@$_GET['action'] == 'delete') {
  $image_name = @$_GET['photo'];
  //echo $image_name;
  unlink( './site_images/media/' . $image_name );
  unlink( './site_images/media/thumbs/'. $image_name );
  $mydb->redirect("./?page=media&msg=deleted");
}

/*==================================Add New Images Insert=========================================================*/

if (isset($_POST['submitImage'])){
  require_once './lib/image-thumbs-crop.php';
  $count = count($_FILES['image']['name']);
  for($i=0; $i<$count; $i++){
    $filename = stripslashes($_FILES['image']['name'][$i]);
    $extension = getExtension($filename);
    $extension = strtolower($extension);
    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
      $mydb->redirect("./?page=media&msg=image_error_extension");
    } else {
      $image_name = $filename;
      $newname = "site_images/media/".$image_name;
      $copied = copy($_FILES['image']['tmp_name'][$i], $newname);
      //Resize Thumbs image
      resize_crop_image(512, 512, "site_images/media/". $image_name, "site_images/media/thumbs/". $image_name, $extension);
    }
  }

  $mydb->redirect("./?page=media&msg=imageUploaded");
}
/*================================== End of Add New Images Insert=========================================================*/


/*============================================================ Messages ===========================================================================*/
if (@$_GET['msg'] == "imageCopyError") {
  echo "<script type='text/javascript'>sweetAlert('Image Copy Error','Something Went Wrong!','error');</script>";
} elseif (@$_GET['msg'] == "imageExtError") {
  echo "<script type='text/javascript'>sweetAlert('Image Extension Error','Best Image Format: jpg, png, gif','error');</script>";
} elseif (@$_GET['msg'] == "deleted") {
  echo "<script type='text/javascript'>swal('Success!', 'Image Deleted', 'success')</script>";
} elseif (@$_GET['msg'] == "deleted-error") {
  echo "<script type='text/javascript'>sweetAlert('Delete Error','Image Not Deleted','error');</script>";
} elseif (@$_GET['msg'] == "imageUploaded") {
  echo "<script type='text/javascript'>swal('Success!', 'Images Uploaded', 'success')</script>";
}
/*============================================================ // Messages Ends ===================================================================*/
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <small>Media</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Media</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="box box-primary">
     <div class="box-header with-border">
       <h3 class="box-title">About Gallery 
       </div>
       <?php 
       $mainFolder    = './site_images/media';
       $extensions    = array(".jpg",".png",".gif",".JPG",".PNG",".GIF");


       /* ---------------------------SHOW PHOTOS INSIDE CONTENT GALLERY ALBUMS FOLDER -----------------------------*/
       $src_folder = $mainFolder;
       $src_files  = scandir($src_folder);

       $files = array();
       foreach ($src_files as $file) {
        $ext = strrchr($file, '.');
        if(in_array($ext, $extensions)) {

         array_push( $files, $file );

         $thumb = $src_folder.'/thumbs/'.$file;
         if (!file_exists($thumb)) {
           echo "No Thumb Images"; 

         }

       }
     }
     if ( count($files) == 0 ) {

      ?>
      <div class="box-body">
        <ol class="breadcrumb">
          <li><a href="./"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li><a href="?page=media"><i class="fa fa-picture-o" aria-hidden="true"></i> Media - About Gallery</a></li>
          <li class="active"><?=count($files);?> Images</li>
        </ol>
        <div class="col-md-2">
          <div class="polaroid">
            <a href="#" data-toggle="modal" data-target="#addImage" title="Add New Images">
              <img src="./dist/img/add-image.png" class="img-responsive" />
            </a>
            <a href="#" data-toggle="modal" data-target="#addImage" title="Add New Images">
              <p>ADD NEW IMAGES</p>
            </a>
          </div>
        </div>
        <div class="col-md-10 text-center">
          <h2>No photos in this album.</h2>
        </div>
      </div>
      <?php

    }else{
      ?>
      <div class="box-body">
        <ol class="breadcrumb">
          <li><a href="./"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li><a href="?page=media"><i class="fa fa-picture-o" aria-hidden="true"></i> Media - Content Gallery</a></li>
          <li class="active"><?=count($files);?> Images</li>
        </ol>
        <div class="box-body">
          <div class="col-md-2">
            <div class="polaroid">
              <a href="#" data-toggle="modal" data-target="#addImage" title="Add New Images">
                <img src="./dist/img/add-image.png" class="img-responsive" />
              </a>
              <a href="#" data-toggle="modal" data-target="#addImage" title="Add New Images">
                <p>ADD NEW IMAGES</p>
              </a>
            </div>
          </div>
          <?php 
          $count = 1;
          $start = count($files);
          for( $i=0; $i<$start; $i++ ) {

            if( isset($files[$i]) && is_file( $src_folder .'/'. $files[$i] ) ) { 
              ?>
              <div class="col-md-2" style="margin-bottom: 2em;">
                <div class="polaroid">
                <a href="#delete" data-toggle="modal" data-photo="<?=$files[$i];?>"
                   id="delete<?=$files[$i];?>" onClick="delete_photo('<?=$files[$i];?>')"><small style="color:red;"><i
                                                class="fa fa-trash"></i> Delete</small></a>
                  <a href="<?=$src_folder;?>/<?=$files[$i];?>" target="_blank">
                    <img src="<?=$src_folder;?>/thumbs/<?=$files[$i];?>" class="img-responsive" />
                  </a>
                  <p><?=$files[$i];?>
                   </p>
                 </div>
               </div>
               <?php

             } else {

              if( isset($files[$i]) ) {
                echo $files[$i];
              }

            }

            /*if ($count%6 == 0) {
              echo "<div class='clearfix'></div><p>&nbsp;</p>";
            }*/
            $count++;
          }
          ?>
        </div>
      </div>
      <?php

    }
    /* ------------------------- END OFSHOW PHOTOS INSIDE CONTENT GALLERY ALBUMS FOLDER ---------------------------*/
    ?>

  </div> 

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
  function delete_photo(image) {
    var conn = './?page=media&action=delete&photo=' + image;
    $('#delete a').attr("href", conn);
  }

</script>

<!-- Add Image Modal -->
<div class="modal fade" style="max-height: 250px; width:500px;" id="addImage">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add New Image</h4>
        </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-lg-12">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-file-picture-o"></i>Image</span>
                  <input class="btn btn-info btn-flat" type="file" name="image[]" multiple required >
                </div>
                <p>Note:* Image with the same name might be replaced.</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="submitImage"><i class="fa fa-plus"></i>
              Add New Image
            </button>
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- //Add Album Modal Ends -->
  <div id="delete" class="modal">
    <div class="modal-header">
      <button data-dismiss="modal" class="close" type="button">&times;</button>
      <h3>Delete Photo</h3>
    </div>
    <div class="modal-body">
      <p>
        Do you want to delete Photo?
      </p>
    </div>
    <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
      class="btn btn-danger"
      href="#">Cancel</a></div>
    </div>