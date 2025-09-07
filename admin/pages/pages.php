<?php

$id = @$_GET['id'];

if(isset($_POST['submit'])){

    $updateData = array("title" => $_POST['title'], "sub_title" => $_POST['sub_header'],"short_content" => $_POST['short_content'],"content" => $_POST['content'],"updated" => date('Y-m-d H:i:s'),"updated_by" => $_SESSION['username']);
    $mydb->where("id", $id);
	$update = $mydb->update("tbl_about", $updateData);

	if($update){
	$mydb->redirect("./?page=pages&id=".$id."&msg=updated");
	} else { 
	$mydb->redirect("./?page=pages&id=".$id."&msg=dberror");
	}
}
?>


<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>Page Control&nbsp;&nbsp;<a href="./?page=pages-main" class="btn btn-success" title="Go to List"><i class="fa fa-arrow-circle-left"></i> Go to List</a>&nbsp;&nbsp;<button class="btn btn-primary" id="edit" title="Edit Page"><i class="fa fa-edit"></i> Edit Page</button><button id="view" class="btn btn-info"><i class="fa fa-eye"></i> View Page</button></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
    <li>Pages</li>
    <li class="active"><?php echo $mydb->select_field("title", "tbl_about","id='".$id."'"); ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <!-- SELECT2 EXAMPLE -->
  	<form id="admin-form" method="post" enctype="multipart/form-data" action="">
    
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title" id='page-title'><?php echo $mydb->select_field("title", "tbl_about","id='".$id."'"); ?></h3>
        <div class="box-tools pull-right">
          <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-11">
          
          	<?php if(@$_GET['msg'] == 'updated'){ ?>
              <div class="alert alert-success alert-dismissable">
              <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i class="fa fa-remove"></i></button>
              <h4>
              <i class="icon fa fa-check"></i>
              Succesfully Updated!
              </h4>
              Page Conetent have been Updated. 
              </div>
            <?php } elseif(@$_GET['msg'] == 'dberror'){ ?>
              <div class="alert alert-info alert-dismissable">
              <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i class="fa fa-remove"></i></button>
              <h4>
              <i class="icon fa fa-check"></i>
              Error!
              </h4>
              Data couldnot be added</div>
			<?php } ?>

            <div class="box-body">
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-file-o"></i>&nbsp;&nbsp;Page Title</span>
                <input type="text" class="form-control" placeholder="Page Title" name="title" value="<?php echo $mydb->select_field("title", "tbl_about","id='".$id."'"); ?>" />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-file-o"></i>&nbsp;&nbsp;Sub Title</span>
                <input type="text" class="form-control" placeholder="Sub heading" name="sub_header" value="<?php echo $mydb->select_field("sub_title", "tbl_about","id='".$id."'"); ?>" />
              </div>
              <br>
              <div  class="input-group"><span class="input-group-addon"><i class="fa fa-newspaper-o"></i> Short Content</span>
                <textarea class="ckeditor" name="short_content" id="editor1" required ><?php echo $mydb->select_field("short_content", "tbl_about","id='".$id."'"); ?></textarea>
              </div>
              <br>
              <div  class="input-group"><span class="input-group-addon"><i class="fa fa-newspaper-o"></i> Long Content </span>
                <textarea class="ckeditor" name="content" id="editor1" required ><?php echo $mydb->select_field("content", "tbl_about","id='".$id."'"); ?></textarea>
              </div>
              
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i> Save Changes</button>&nbsp;&nbsp;<a href="?page=pages-main" id="cancel" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a></div>
    </div>
  </form>
</section>
<!-- /.content -->
</div>

<script type="text/javascript">

$(document).ready(function() {
$('#admin-form input').prop('readonly', true);
$('#admin-form button[type=submit]').hide();
$('#view').hide();
$('#cancel').hide();
});

$("#edit").click(function(){
$('#admin-form input').prop('readonly', false);						  
$('#admin-form button[type=submit]').show();
$('#page-title').html("Edit Page");
$('#edit').hide();
$('#view').show();
$('#cancel').show();
}); 

$("#view").click(function(){
$('#admin-form input').prop('readonly', true);
$('#admin-form button[type=submit]').hide();
$('#page-title').html("<?php echo $mydb->select_field("title", "tbl_about","id='".$id."'"); ?>");
$('#view').hide();
$('#edit').show();
$('#cancel').hide();
});

</script>
