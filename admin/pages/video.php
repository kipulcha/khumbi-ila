<?

if(isset($_POST['submit'])){

include_once('slug.php');

$slug = slug($_POST['title']);
	
$updatesql = $mydb->update_sql("tbl_videos", array("title","slug","youtube_id","content","featured","updated","updated_by"), array($_POST['title'],$slug,$_POST['youtube'],$_POST['content'], $_POST['type'], date('Y-m-d H:i:s'),$_SESSION['username']), "id = '".$_GET['id']."'");

if($updatesql){
$mydb->redirect("./?page=video&msg=updated");
} else {
$mydb->redirect("./?page=video&msg=dberror");
}

}


if($_GET['action']){

//Videos Delete

if($_GET['action'] == 'delete') {
$delstatus = $mydb->delete_sql("tbl_videos","id='".$_GET['id']."'");

$mydb->redirect("./?page=video&msg=deleted");
}


elseif($_GET['action'] == 'edit') {

//Videos Edit
?>

<div class="content-wrapper">

<section class="content-header">
  
  <h1>Videos&nbsp;&nbsp;<a href="./?page=video-add"><button class="btn btn-primary" title="Add Video"><i class="fa fa-plus"></i> Add Video</button></a>&nbsp;&nbsp;<a href="./?page=video" class="btn btn-success" title="Go to List"><i class="fa fa-arrow-circle-left"></i> Go to List</a></h1>
  
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Videos</a></li>
    <li class="active"><a href="#">Edit Page</a></li>
  </ol>

</section>

<section class="content">
  	
    <form id="video-form" method="post" enctype="multipart/form-data" action="">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title" id='page-title'>Edit Video</h3>
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
                  <input type="text" class="form-control" name="title" placeholder="Video Title" value="<?php echo $mydb->select_field("title", "tbl_videos","id='".$_GET['id']."'"); ?>" required />
                </div></div>
                
                <div class="col-lg-6"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-youtube"></i> Youtube ID</span>
                	<input type="text" class="form-control" name="youtube" placeholder="Youtube Video ID" value="<?php echo $mydb->select_field("youtube_id", "tbl_videos","id='".$_GET['id']."'"); ?>" required />
                </div></div>
                </div>
                <br>
                
                <div class="row">
                <?php $featured = $mydb->select_field("featured", "tbl_videos","id='".$_GET['id']."'"); ?>
              	<div class="col-lg-6"><div class="input-group"> <span class="input-group-addon"><input name="type" type="checkbox" value="1" <?php echo($featured == 1? 'checked="checked"':'');?>/></span>
                    <input type="text" class="form-control" value="Featured Video" readonly />
                </div></div>
            	
                </div>
            	<br />

                <div  class="input-group"><span class="input-group-addon"><i class="fa fa-newspaper-o"></i> Description</span>
                <textarea class="ckeditor" name="content" id="editor1" required ><?php echo $mydb->select_field("content", "tbl_videos","id='".$_GET['id']."'"); ?></textarea>
              	</div>
                
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i> Save changes</button>&nbsp;&nbsp;
          <a href="?page=video" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
        </div>
      </div>
    </form>
  
</section>

</div>

<? }} else { ?>

<!--Videos List-->

<div class="content-wrapper">

  <section class="content-header">
  <h1>Videos&nbsp;&nbsp;<a href="./?page=video-add"><button class="btn btn-primary" title="Add Video"><i class="fa fa-plus"></i> Add Video</button></a></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
    <li>Videos</li>
    <li class="active">List</li>
  </ol>
</section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">

        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Videos List</h3>
          </div>

          <div class="box-body">
          	
            <? if($_GET['msg'] == 'added'){ ?>
              <div class="alert alert-success alert-dismissable">
              <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i class="fa fa-remove"></i></button>
              <h4>
              <i class="icon fa fa-check"></i>
              Succesfully Added!
              </h4>
              Video has been Added. 
              </div>
			<? } elseif($_GET['msg'] == 'updated'){ ?>
              <div class="alert alert-success alert-dismissable">
              <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i class="fa fa-remove"></i></button>
              <h4>
              <i class="icon fa fa-check"></i>
              Succesfully Updated!
              </h4>
              Video has been Updated. 
              </div>
			<? } elseif($_GET['msg'] == 'deleted'){ ?>
              <div class="alert alert-info alert-dismissable">
              <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i class="fa fa-remove"></i></button>
              <h4>
              <i class="icon fa fa-check"></i>
              Succesfully Deleted!
              </h4>
              Video has been Deleted. 
              </div>
            <? } elseif($_GET['msg'] == 'dberror'){ ?>
              <div class="alert alert-info alert-dismissable">
              <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i class="fa fa-remove"></i></button>
              <h4>
              <i class="icon fa fa-check"></i>
              Error!
              </h4>
              Data couldnot be added</div>
			<? } ?>
            <table id="video-table" class="table table-bordered table-striped">
              
              <thead>
                <tr>
                  <th>S.N</th>
                  <th>Video Title</th>
                  <th>Inserted On</th>
                  <th>Updated On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              
              <tbody>
              
			  <?
			  $result = mysql_query("SELECT * FROM `tbl_videos` ORDER BY `updated` DESC");
			  if(mysql_num_rows($result) > 0){
			  $i = 1;
			  while($row = mysql_fetch_array($result)) { 
			  ?>
             
             	<tr>
                  <td class="text-center"><?=$i;?>.</td>
                  <td><? echo($row['featured'] == '1' ? '<i class="fa fa-star" style="color:#FFD700"></i>&nbsp;&nbsp;' : ''); ?><?=$row['title'];?>&nbsp;&nbsp;<a href="https://www.youtube.com/watch?v=<?=$row['youtube_id'];?>" target="_blank"><span class="label label-success"><i class="fa fa-youtube-play"></i> Play Video</span></a></td>
                  <td class="text-center"><?=date("F d, Y", strtotime($row['inserted']));?><br /><small style="font-style:italic;"><?php echo ($row['inserted_by'] ? "by " .$row['inserted_by'] : "" ); ?></small></td>
                  <td class="text-center"><? echo( $row['updated'] == '0000-00-00 00:00:00' ? '<small style="font-style:italic;">No Updates Made</small>' : date("F d, Y", strtotime($row["updated"])).'<br /><small style="font-style:italic;">by '.$row[updated_by].'</small>'); ?></td>
                  <td class="text-center">
                  <!--Edit-->
                  <a href="./?page=video&action=edit&id=<?=$row['id'];?>"><button type="button" class="btn btn-success"><i class="fa fa-edit"></i> Edit</button></a>&nbsp;&nbsp;
                  <!--Delete-->
                  <a href="#delete" data-toggle="modal" data-id="<?=$row['id'];?>" id="delete<?=$row['id'];?>" class="btn btn-danger" onClick="delete_video(<?=$row['id'];?>)"><i class="fa fa-trash"></i> Delete</a>
                  </td>
                </tr>
              
			  <? $i++; }} else {  ?>
              
              	<tr>
                  <td colspan="5" class="text-center" style="color:#903;">No Videos Listed</td>
                </tr>
              
			  <? } ?>
              
              </tbody>
              
              <tfoot>
                <tr>
                  <th class="text-center" colspan="5"><?php echo C_NAME;?> Videos Table</th>
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
function delete_video(id){
var conn = './?page=video&action=delete&id='+id;
$('#delete a').attr("href", conn);
}
</script>
<div id="delete" class="modal">
  <div class="modal-header">
	<button data-dismiss="modal" class="close" type="button">&times;</button>
	<h3>Delete Videos</h3>
  </div>
  <div class="modal-body">
	<p>
	  Are you sure you want to delete?
	</p>
  </div>
  <div class="modal-footer"> <a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal" class="btn btn-danger" href="#">Cancel</a> </div>
</div>

<? } ?>
<script type="text/javascript">
  $(function () {
	$('#video-table').DataTable({
	  "paging": true,
	  "lengthChange": false,
	  "searching": true,
	  "ordering": false,
	  "info": true,
	  "autoWidth": false
	});
  });
</script>