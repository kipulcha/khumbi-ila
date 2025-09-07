<?php if (isset($_POST['submit'])){
$images_dir = "site_images/photo_gallery";
$result_final = "";
$counter = 0;
$known_photo_types = array( 
					'image/pjpeg' => 'jpg',
					'image/jpeg' => 'jpg',
					'image/gif' => 'gif',
					'image/bmp' => 'bmp',
					'image/x-png' => 'png'
				);

$gd_function_suffix = array( 
					'image/pjpeg' => 'JPEG',
					'image/jpeg' => 'JPEG',
					'image/gif' => 'GIF',
					'image/bmp' => 'WBMP',
					'image/x-png' => 'PNG'
				);

$photos_uploaded = @$_FILES['photo_filename'];

while( $counter <= count($photos_uploaded) )
{
	if($photos_uploaded['size'][$counter] > 0)
	{
		if(!array_key_exists($photos_uploaded['type'][$counter], $known_photo_types))
		{
			$result_final .= "File ".($counter+1)." is not a photo<br />";
		}
		else
		{
			//insert data
			$insertData = array("filename" => "0", "album_name" => $_POST['album_name'], "caption" => $_POST['photo_caption'], "created" => $currentDate, "created_by" => $_SESSION['username']);
			$insert = $mydb->insert("tbl_gallery", $insertData);

			$new_id 	= $mydb->lastInsertId;
			$filetype 	= $photos_uploaded['type'][$counter];
			$extention 	= $known_photo_types[$filetype];
			$filename 	= $new_id.".".$extention;

			$updateData = array("filename" => addslashes($filename));
			$mydb->where("id", addslashes($new_id));
			$update = $mydb->update("tbl_gallery", $updateData);
			// Store the orignal file
			copy($photos_uploaded['tmp_name'][$counter], $images_dir."/".$filename);
			
			// Let's get the Thumbnail size
			$size = GetImageSize( $images_dir."/".$filename );
			if($size[0] > $size[1])
			{
				$thumbnail_width = 400;
				$thumbnail_height = (int)(400 * $size[1] / $size[0]);
			}
			else
			{
				$thumbnail_width = (int)(400 * $size[0] / $size[1]);
				$thumbnail_height = 400;
			}
		
			// Build Thumbnail with GD 1.x.x, you can use the other described methods too
			$function_suffix 	= $gd_function_suffix[$filetype];
			$function_to_read 	= "ImageCreateFrom".$function_suffix;
			$function_to_write 	= "Image".$function_suffix;

			// Read the source file
			$source_handle = $function_to_read ( $images_dir."/".$filename ); 
			
			if($source_handle)
			{
				// Let's create an blank image for the thumbnail
				$destination_handle = imagecreatetruecolor( $thumbnail_width, $thumbnail_height );
			
				// Now we resize it
				imagecopyresampled( $destination_handle, $source_handle, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $size[0], $size[1] );
			}

			// Let's save the thumbnail
			$function_to_write( $destination_handle, $images_dir."/thumb_".$filename );
			ImageDestroy($destination_handle );
			//

		}
	}
$counter++;
}

$mydb->redirect("./?page=image-gallery-view&msg=added");

} ?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Image Gallery&nbsp;&nbsp;<a href="./?page=image-gallery-view"><button class="btn btn-success" title="Image Gallery"><i class="fa fa-eye"></i> View Gallery</button></a></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
      <li>Gallery</li>
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
            <!-- /.box-header -->
            <div class="box-body">
            
              <div class="row">
                <div class="col-md-8">
                  <div class="box-body">
                    <div class="input-group"> <span class="input-group-addon"><i class="fa fa-bullhorn"></i> Album Name</span>
                      <input type="text" class="form-control" name="album_name" placeholder="Album name" <?php echo(@$_GET['gal'] == '' ? '' : 'value = "'.base64_decode(@$_GET['gal']).'" readonly="readonly"' );?> required />
                    </div>
                    <br />

                    <div class="input-group form-group"><span class="input-group-addon"> <i class="fa fa-file-picture-o"></i> Select Photos</span>
                      <input class="btn btn-info btn-flat" name='photo_filename[]' type="file" multiple required />
                    </div>
                    
                    <div class="input-group"> <span class="input-group-addon"><i class="fa fa-file-picture"></i> Photo Caption</span>
                      <input type="text" class="form-control" name="photo_caption" placeholder="Photo Caption" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.row -->
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Photos</button>
              &nbsp;&nbsp;<a href="./?page=image-gallery" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a> </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>