<?php

$id = @$_GET['id'];

if (isset($_POST['submit'])) {

    $updateData = array("title" => $_POST['title'], "address" => $_POST['address'], "phone" => $_POST['phone'], "fax" => $_POST['fax'], "mobile" => $_POST['mobile'], "email" => $_POST['email'], "facebook" => $_POST['facebook'], "twitter" => $_POST['twitter'], "linkedin" => $_POST['linkedin'], "google" => $_POST['google'], "map" => $_POST['map'], "updated" => date('Y-m-d H:i:s'), "updated_by" => $_SESSION['username']);
    $mydb->where("id", $id);
    $update = $mydb->update("tbl_contact", $updateData);

    if (@$update) {
        $mydb->redirect("./?page=contact&msg=updated");
    } else {
        $mydb->redirect("./?page=contact&msg=dberror");
    }

}


if (@$_GET['action']) {

//Branch Office Edit

    if (@$_GET['action'] == 'delete') {
        $mydb->where("id", $id);
        $delete =$mydb->delete("tbl_contact");
        if(@$delete) {
            $mydb->redirect("./?page=contact&msg=deleted");
        } else {
            $mydb->redirect("./?page=contact&msg=deleted-error");
        }

    } elseif (@$_GET['action'] == 'edit') {

//Branch Office Edit
?>

        <div class="content-wrapper">

            <section class="content-header">
                <h1>Contact Page&nbsp;&nbsp;<a href="./?page=contact-add">
                        <!-- <button class="btn btn-primary" title="Add Contact"><i class="fa fa-plus"></i> Add New</button> -->
                    </a>&nbsp;&nbsp;</h1>
                <ol class="breadcrumb">
                    <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li>Contact Page</li>
                    <li class="active">Edit</li>
                </ol>
            </section>

            <section class="content">

                <form id="contact-form" method="post">

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title" id='page-title'>Edit Contact Details</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-info" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-danger" data-widget="remove"><i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-11">

                                    <div class="box-body">
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-bank"></i> Title</span>
                                            <input type="text" class="form-control" name="title" placeholder="Title"
                                                   value="<?php echo $mydb->select_field("title", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-map-marker"></i> Address</span>
                                            <input type="text" class="form-control" name="address" placeholder="Address"
                                                   value="<?php echo $mydb->select_field("address", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-phone"></i> Telephone</span>
                                            <input type="text" class="form-control" name="phone"
                                                   placeholder="Phone Number"
                                                   value="<?php echo $mydb->select_field("phone", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-mobile"></i> Mobile</span>
                                            <input type="text" class="form-control" name="mobile"
                                                   placeholder="Mobile Number"
                                                   value="<?php echo $mydb->select_field("mobile", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-fax"></i> Fax</span>
                                            <input type="text" class="form-control" name="fax"
                                                   placeholder="Fax Number"
                                                   value="<?php echo $mydb->select_field("fax", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-envelope"></i> E-Mail</span>
                                            <input type="text" class="form-control" name="email"
                                                   placeholder="E-Mail Address"
                                                   value="<?php echo $mydb->select_field("email", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-facebook"></i> Facebook</span>
                                            <input type="text" class="form-control" name="facebook"
                                                   value="<?php echo $mydb->select_field("facebook", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-twitter"></i> Twitter</span>
                                            <input type="text" class="form-control" name="twitter"
                                                   value="<?php echo $mydb->select_field("twitter", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-linkedin"></i> LinkedIn</span>
                                            <input type="text" class="form-control" name="linkedin"
                                                   value="<?php echo $mydb->select_field("linkedin", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-google-plus"></i> Google+</span>
                                            <input type="text" class="form-control" name="google"
                                                   value="<?php echo $mydb->select_field("google", "tbl_contact", "id='" . $id . "'"); ?>"/>
                                        </div>
                                        <br>
                                        <div class="input-group"><span class="input-group-addon"><i
                                                    class="fa fa-location-arrow"></i> Google Maps</span>
                                            <textarea name="map" class="col-md-12"
                                                      placeholder="Google Map Embed Code"><?php echo $mydb->select_field("map", "tbl_contact", "id='" . $id . "'"); ?></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i>
                                Save Changes
                            </button>
                            &nbsp;&nbsp;<a href="?page=contact" id="cancel" class="btn btn-danger"><i
                                    class="fa fa-remove"></i> Cancel</a></div>
                    </div>

                </form>

            </section>

        </div>

    <?php }
} else { ?>

    <!--Branch Office List-->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Contact Page&nbsp;&nbsp;<a href="./?page=contact-add">
                    <!-- <button class="btn btn-primary" title="Add Branch Office"><i class="fa fa-plus"></i> Add New
                    </button> -->
                </a>&nbsp;&nbsp;</h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Contact Page</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Contact Page</h3>
                        </div>

                        <div class="box-body">

                            <?php if (@$_GET['msg'] == 'added') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Added!
                                    </h4>
                                    Branch Office's Contact Details has been Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'updated') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Updated!
                                    </h4>
                                    Branch Office's Contact Details have been Updated.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Succesfully Deleted!
                                    </h4>
                                    Branch Office's Contact Details have been Deleted.
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
                            <?php } elseif (@$_GET['msg'] == 'deleted-error') { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-warning"></i>
                                        Error while Deleting Records!
                                    </h4>
                                    Data could not be Deleted.
                                </div>
                            <?php } ?>
                            <table id="contact-table" class="table table-bordered table-striped">

                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Title</th>
                                    <th>Telephone</th>
                                    <th>Inserted On</th>
                                    <th>Updated On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                $result = $mydb->select("tbl_contact");
                                $count = 1;
                                if ($mydb->totalRows > 0) {
                                    foreach ($result as $row):
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $count; ?>.</td>
                                            <td><strong><?= $row['title']; ?></strong></td>
                                            <td class="text-center"><?= $row['phone']; ?></td>
                                            <td class="text-center"><?= date("F d, Y", strtotime($row['inserted'])); ?>
                                                <br/>
                                                <small
                                                    style="font-style:italic;"><?php echo($row['inserted_by'] ? "by " . $row['inserted_by'] : ""); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row['updated'] !== '0000-00-00 00:00:00') { ?>
                                                    <?php echo date("F d, Y", strtotime($row['updated'])); ?><br/>
                                                    <small
                                                        style="font-style:italic;"><?php echo($row['updated_by'] ? "by " . $row['updated_by'] : ""); ?></small>
                                                <?php } else { ?>
                                                    <?php echo '<i>No Updates Made'; ?>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <!--Edit-->
                                                <a href="./?page=contact&action=edit&id=<?= $row['id']; ?>">
                                                    <button type="button" class="btn btn-success"><i
                                                            class="fa fa-edit"></i> Edit
                                                    </button>
                                                </a>&nbsp;&nbsp;
                                                <!--Delete-->
                                               <!--  <a href="#delete_branch" data-toggle="modal"
                                                   data-id="<?= $row['id']; ?>" id="delete_branch<?= $row['id']; ?>"
                                                   class="btn btn-danger" onClick="delete_branch(<?= $row['id']; ?>)"><i
                                                        class="fa fa-trash"></i> Delete</a> -->
                                            </td>
                                        </tr>

                                        <?php
                                        $count++;
                                    endforeach;
                                } else { ?>

                                    <tr>
                                        <td colspan="6" class="text-center" style="color:#903;">No Branches Listed</td>
                                    </tr>

                                <?php } ?>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="6"><?php echo C_NAME; ?> Contact Table</th>
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
    <script>
        function delete_branch(id) {
           /* var conn = './?page=contact&action=delete&id='+id;
            $('#delete_branch a').attr("href", conn);*/
        }

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
    </script>
    <!-- <div id="delete_branch" class="modal">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3>Delete Contact</h3>
        </div>
        <div class="modal-body">
            <p>
                Do you want to delete this Contact?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div> -->

<?php } ?>
