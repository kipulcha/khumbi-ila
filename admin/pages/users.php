<?php
if (@$_SESSION['user_group'] == 1) {
    $mydb->redirect("./?page=404");
}

//Main Page
if (@$_GET['action']) {

//User Delete

    if (@$_GET['action'] == 'delete') {

        $mydb->where("id", @$_GET['id']);
        $delete =$mydb->delete("tbl_admin");
        if(@$delete) {
            $mydb->redirect("./?page=users&msg=deleted");
        } else {
            $mydb->redirect("./?page=users&msg=deleted-error");
        }

    }

} else { ?>

    <!--Trainings List-->

    <div class="content-wrapper">

        <section class="content-header">
            <h1>User Profile&nbsp;&nbsp;<a href="./?page=user-add" class="btn btn-success" title="View User"><i
                        class="fa fa-user-plus"></i> Add User</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>User Setting</li>
                <li class="active">List</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">

                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">User List</h3>
                        </div>

                        <div class="box-body">

                            <?php
                            // Notifications

                            if (@$_GET['msg'] == 'added') { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Successfully Added!
                                    </h4>
                                    User has been Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'deleted') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Successfully Deleted!
                                    </h4>
                                    User has been Deleted.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'dberror') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Error!
                                    </h4>
                                    Data could not be Added.
                                </div>
                            <?php } elseif (@$_GET['msg'] == 'delete-error') { ?>
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Error!
                                    </h4>
                                    User could not be Deleted.
                                </div>
                            <?php } ?>

                            <table id="offer-table" class="table table-bordered table-striped">

                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Full Name</th>
                                    <th>User Group</th>
                                    <th>Inserted On</th>
                                    <th>Updated On</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php
                                $mydb->where("id", "2", "!=");
                                $result = $mydb->select("tbl_admin");
                                $count = 1;
                                if ($mydb->totalRows > 0) {
                                    foreach ($result as $row):
                                        ?>
                                        <tr>

                                            <td class="text-center"><?= $count; ?>.</td>
                                            <td class="text-center"><?= $row['name'] ?></td>
                                            <td class="text-center"><?php echo($row['user_group'] == 0 ? 'Admin' : 'General User'); ?></td>
                                            <td class="text-center"><?= date("F d, Y", strtotime($row['created'])); ?>
                                                <br/>
                                                <small
                                                    style="font-style:italic;"><?php echo($row['created_by'] ? "by " . $row['created_by'] : ""); ?></small>
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
                                                <!--View-->
                                                <a href="./?page=user&id=<?= $row['id']; ?>">
                                                    <button type="button" class="btn btn-success"><i
                                                            class="fa fa-eye"></i> View Profile
                                                    </button>
                                                </a>&nbsp;&nbsp;
                                                <!--Delete-->
                                                <?php
                                                echo(( $row['user_group'] == 1 || $row['id'] == $_SESSION['userid'] ) ? '<a href="#delete" data-toggle="modal" data-id="' . $row["id"] . '" id="delete' . $row["id"] . '" class="btn btn-danger" onClick="delete_user(' . $row["id"] . ')"><i class="fa fa-trash"></i> Delete</a>' : '');
                                                ?>
                                            </td>

                                        </tr>

                                        <?php
                                        $count++;
                                    endforeach;
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center" style="color:#903;">No Users Listed</td>
                                    </tr>

                                <?php } ?>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="text-center" colspan="6"><?php echo C_NAME; ?> Users Table</th>
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
        function delete_user(id) {
            var conn = './?page=users&action=delete&id=' + id;
            $('#delete a').attr("href", conn);
        }
    </script>
    <div id="delete" class="modal">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3>Delete User</h3>
        </div>
        <div class="modal-body">
            <p>
                Do you want to delete user?
            </p>
        </div>
        <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                    class="btn btn-danger" href="#">Cancel</a>
        </div>
    </div>

<?php } ?>
<script type="text/javascript">
    $(function () {
        $('#offer-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    });

    $(function () {
        $('input[name="daterange"]').daterangepicker();
    });
</script>