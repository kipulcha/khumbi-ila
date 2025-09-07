<?php
//Gallery Pic Delete

$id = @$_GET['id'];

if (@$_GET['action'] == 'delete') {

    $filename = $mydb->select_field("filename", "tbl_gallery", "id='" . $_GET['id'] . "'");

    $mydb->where("id", $id);
    $delete = $mydb->delete("tbl_gallery");
    if (@$delete) {
        $mydb->redirect("./?page=image-gallery-view&msg=deleted");
    } else {
        $mydb->redirect("./?page=image-gallery-view&msg=deleted-error");
    }
}
?>

<div class="content-wrapper" id="content">
    <section class="content-header">
        <h1>Image Gallery&nbsp;&nbsp;<a href="./?page=image-gallery">
                <button class="btn btn-success" title="Image Gallery"><i class="fa fa-plus"></i> Add Photos</button>
            </a></h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Gallery</li>
            <li class="active">Image Gallery View</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">View Images</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <?php
                        // Notifications

                        if (@$_GET['msg'] == 'caption-added') { ?>
                            <div class="alert alert-success alert-dismissable">
                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                        class="fa fa-remove"></i></button>
                                <h4>
                                    <i class="icon fa fa-check"></i>
                                    Caption Updated
                                </h4>
                                Caption has been Updated.
                            </div>
                        <?php } elseif (@$_GET['msg'] == 'caption-error') { ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                        class="fa fa-remove"></i></button>
                                <h4>
                                    <i class="icon fa fa-check"></i>
                                    Error!
                                </h4>
                                Error while updating caption.
                            </div>
                        <?php } elseif (@$_GET['msg'] == 'added') { ?>
                            <div class="alert alert-success alert-dismissable">
                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                        class="fa fa-remove"></i></button>
                                <h4>
                                    <i class="icon fa fa-check"></i>
                                    Succesfully Added!
                                </h4>
                                Photos have been added to Gallery.
                            </div>

                        <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                            <div class="alert alert-info alert-dismissable">
                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                        class="fa fa-remove"></i></button>
                                <h4>
                                    <i class="icon fa fa-check"></i>
                                    Succesfully Deleted!
                                </h4>
                                Gallery Photo has been Deleted.
                            </div>
                        <?php }

                        $mydb->columns = array("DISTINCT album_name");
                        $result = $mydb->select("tbl_gallery");

                        if ($mydb->totalRows > 0) {
                            foreach ($result as $rw):

                                $album_name = $rw['album_name'];
                                ?>

                                <div class="box-header with-border" style="margin-bottom:1em;">

                                    <h3 class="box-title">
                                        <?=$album_name; ?>
                                        &nbsp;&nbsp;<a
                                            href="./?page=image-gallery&gal=<?= base64_encode($album_name); ?>"
                                            class="btn btn-success" title="Add Image"><i class="fa fa-plus"></i> Add
                                            Photos</a></h3>

                                </div>

                                <?php
                                $mydb->where("album_name", $album_name, "=");
                                $mydb->orderByCols = array("created");
                                $result = $mydb->select("tbl_gallery");
                                $count = 1;
                                foreach ($result as $row):
                                    ?>
                                    <div class="col-lg-2" style="min-height:150px; padding-bottom:1em;">
                                        <a href="#caption" data-toggle="modal" data-id="<?=$row['id']; ?>"
                                           data-caption="<?=$row['caption']; ?>" id="caption<?=$row['id']; ?>"
                                           onClick="photo_caption(<?=$row['id']; ?>)"><i class="fa fa-edit"></i>Caption</a>&nbsp;&nbsp;
                                        <a href="#delete" data-toggle="modal" data-id="<?=$row['id']; ?>"
                                           id="delete<?=$row['id']; ?>" onClick="delete_photo(<?=$row['id']; ?>)"><i
                                                class="fa fa-trash"></i> Delete</a>
                                        <img class="img-responsive"
                                             src="site_images/photo_gallery/thumb_<?=$row['filename']; ?>"
                                             alt="<?=$row['caption']; ?>" title="<?=$row['caption']; ?>"/>
                                    </div>
                                    <?php echo($count % 6 == 0 ? '<div class="clearfix"> </div>' : ''); ?>
                                    <?php $count++;
                                endforeach;
                            echo '<div class="clearfix"> </div>';
                            endforeach;
                        } else { ?>
                            <h3 style="text-align:center; padding:50px 0;">No Images to display</h3>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--Delete Modal-->
<script>
    function delete_photo(id) {
        var conn = './?page=image-gallery-view&action=delete&id=' + id;
        $('#delete a').attr("href", conn);
    }

    function photo_caption(id) {
        var caption = $('#caption' + id).attr('data-caption');
        $('#caption_text').val(caption);
        $('#caption_id').val(id);
        $('#caption_table').val('tbl_gallery');
    }
</script>

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

<div id="caption" class="modal">
    <form name="caption-form" action="./pages/caption.php" method="post" enctype="multipart/form-data">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3>Photo Caption</h3>
        </div>
        <div class="modal-body">
            <p id="sec_text">Please enter photo caption:</p>
            <input type="text" class="form-control" placeholder="Picture Caption" name="caption_text"
                   id="caption_text"/>
            <input type="hidden" class="form-control" name="caption_id" id="caption_id"/>
            <input type="hidden" class="form-control" name="caption_table" id="caption_table"/>
            <input type="hidden" class="form-control" name="caption_url" id="caption_url"
                   value="./?page=image-gallery-view"/>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Confirm</button>
            <a data-dismiss="modal" class="btn btn-danger" href="#">Cancel</a></div>
    </form>
</div>