<?php
if ($_SESSION['user_group'] == 1) {
    $mydb->redirect("./?page=404");
}

if (isset($_POST['submit'])) {

    $updateData = array("keywords" => $_POST['keywords'],"description" =>$_POST['description'],"updated" => $currentDate,"updated_by" => $_SESSION['username']);
    $mydb->where("id", '1');
    $update = $mydb->update("tbl_seo", $updateData);

    if(@$update){
        $mydb->redirect("?page=seo&msg=updated");
    } else {
        $mydb->redirect("?page=seo&msg=dberror");
    }


}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>SEO Settings
            <button class="btn btn-primary" id="edit" title="Edit Page"><i class="fa fa-edit"></i> Edit</button>
            <button id="view" class="btn btn-info"><i class="fa fa-eye"></i> View</button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">SEO Setting</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Form -->
        <form id="seoForm" name="userFrom" method="post" enctype="multipart/form-data" action="">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Search Engine Optimization</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-11">
                            <?php if (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4><i class="icon fa fa-check"></i> Succesfully Updated! </h4>
                                    User Details have been Updated.
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
                            <?php } ?>
                            <div class="box-body">
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-tags"></i>&nbsp;&nbsp;Keywords</span>
                                    <input type="text" class="form-control" placeholder="Keywords" name="keywords"
                                           value="<?php echo $mydb->select_field("keywords", "tbl_seo", "id='1'"); ?>"/>
                                </div>
                                <p style="color:#666">Please seperate each keyword with comma ( , )</p>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i
                                            class="fa fa-newspaper-o"></i> Description</span>
                                    <textarea class="ckeditor" name="description" id="editor1"
                                              required><?php echo $mydb->select_field("description", "tbl_seo", "id='1'"); ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i> Save
                        Changes
                    </button>
                    &nbsp;&nbsp;<a href="?page=users" id="cancel" class="btn btn-danger"><i class="fa fa-remove"></i>
                        Cancel</a></div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#seoForm input').prop('readonly', true);
        $('#seoForm button[type=submit]').hide();
        $('#view').hide();
        $('#cancel').hide();
    });

    $("#edit").click(function () {
        $('#seoForm input').prop('readonly', false);
        $('#seoForm button[type=submit]').show();
        $('#page-title').html("Edit SEO");
        $('#edit').hide();
        $('#view').show();
        $('#cancel').show();
    });

    $("#view").click(function () {
        $('#seoForm input').prop('readonly', true);
        $('#seoForm button[type=submit]').hide();
        $('#page-title').html("Search Engine Optimization");
        $('#view').hide();
        $('#edit').show();
        $('#cancel').hide();
    });
</script>