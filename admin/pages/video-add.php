<?
if(isset($_POST['submit'])){

include_once('slug.php');

$slug = slug($_POST['title']);

$insertsql = $mydb->insert_sql("tbl_videos", array("title","youtube_id","slug","content","featured","inserted","inserted_by","updated","updated_by"), array($_POST['title'], $_POST['youtube'], $slug, $_POST['content'], $_POST['type'],date('Y-m-d H:i:s'),$_SESSION['username'],'',''));

if($insertsql){
$mydb->redirect("./?page=video&msg=added");
} else {
$mydb->redirect("./?page=video&msg=dberror");
}

}
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Video&nbsp;&nbsp;<a href="./?page=video" class="btn btn-success" title="Go to List"><i class="fa fa-arrow-circle-left"></i> Go to List</a></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
      <li>Video</li>
      <li class="active">Add New</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Video Form -->
    <form id="video-form" method="post" enctype="multipart/form-data" action="">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title" id='page-title'>Add Video</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        
       <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-11">
              <div class="box-body">
                
                <div class="row">
                
              	<div class="col-lg-6"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-bullhorn"></i> Video Title</span>
                  <input type="text" class="form-control" name="title" placeholder="Video Title" required />
                </div></div>
                
                <div class="col-lg-6"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-youtube"></i> Youtube ID</span>
                	<input type="text" class="form-control" name="youtube" placeholder="Youtube Video ID" required />
                </div></div>
                </div>
                <br>
                
                <div class="row">
                
              	<div class="col-lg-6"><div class="input-group"> <span class="input-group-addon"><input name="type" type="checkbox" value="1" /></span>
                    <input type="text" class="form-control" value="Featured Video" readonly />
                </div></div>
            	
                </div>
            	<br />

                <div  class="input-group"><span class="input-group-addon"><i class="fa fa-newspaper-o"></i> Description</span>
                <textarea class="ckeditor" name="content" id="editor1" required ></textarea>
              	</div>
                
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Video</button>&nbsp;&nbsp;<a href="?page=video" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
        </div>
      </div>
    </form>
  </section>
  <!-- /.content -->
</div>