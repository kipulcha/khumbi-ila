<?php
if(isset($_POST['submit'])){

  $insertData = array("title" => $_POST['title'], "address" => $_POST['address'], "phone" => $_POST['phone'], "fax" => $_POST['fax'], "mobile" => $_POST['mobile'], "email" => $_POST['email'], "facebook" => $_POST['facebook'], "twitter" => $_POST['twitter'], "linkedin" => $_POST['linkedin'], "google" => $_POST['google'], "map" => $_POST['map'], "inserted" => date('Y-m-d H:i:s'), "inserted_by" => $_SESSION['username'], "updated" => '', "updated_by" => '');
  $insert = $mydb->insert("tbl_contact", $insertData);

	if(@$insert) {
	$mydb->redirect("./?page=contact&msg=added");
	} else { 
	$mydb->redirect("./?page=contact&msg=dberror");
	}
	
}
?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Contact Page&nbsp;&nbsp;<a href="?page=contact"><button class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</button></a></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
    <li>Contact Page</li>
    <li class="active">Add New</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <!-- Contact Form -->
  	
    <form id="contact-form" method="post" enctype="multipart/form-data" action="">
    
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title" id='page-title'>Add Branch</h3>
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
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-bank"></i> Title</span>
                <input type="text" class="form-control" name="title" placeholder="Title" required />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-map-marker"></i> Address</span>
                <input type="text" class="form-control" name="address" placeholder="Address" required />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-phone"></i> Telephone</span>
                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-mobile"></i> Mobile</span>
                <input type="text" class="form-control" name="mobile" placeholder="Mobile Number" required />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-fax"></i> Fax</span>
                <input type="text" class="form-control" name="fax" placeholder="Fax Number" required />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i> E-Mail</span>
                <input type="text" class="form-control" name="email" placeholder="E-Mail Address" required />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-facebook"></i> Facebook</span>
                <input type="text" class="form-control" name="facebook" placeholder="Facebook Link" />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-twitter"></i> Twitter</span>
                <input type="text" class="form-control" name="twitter" placeholder="Twitter Link" />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-linkedin"></i> LinkedIn</span>
                <input type="text" class="form-control" name="linkedin" placeholder="Linked-in Link" />
              </div>
              <br>
              <div class="input-group"> <span class="input-group-addon"><i class="fa fa-google-plus"></i> Google+</span>
                <input type="text" class="form-control" name="google" placeholder="Google Plus Link" />
              </div>
              <br>
              <div  class="input-group"><span class="input-group-addon"><i class="fa fa-location-arrow"></i> Google Maps</span>
                <textarea name="map" class="col-md-12" placeholder="Google Map Embed Code"></textarea>
              </div>
              
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-plus"></i> Add Branch</button>&nbsp;&nbsp;<a href="?page=contact" id="cancel" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a></div>
    </div>
  
  </form>
  
</section>
<!-- /.content -->
</div>