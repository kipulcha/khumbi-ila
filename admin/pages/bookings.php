<?php

$id = @$_GET['id'];

if (@$_GET['action'] == 'status_change') {

    $statusOld = $mydb->select_field("status", "tbl_bookings", "id='" . $id . "'");

    if ($statusOld == '1'):
        $statusNew = '0';
    elseif ($statusOld == '0'):
        $statusNew = '1';
    endif;

    $updateData = array("status" => $statusNew);
    $mydb->where("id", $id);
    $update = $mydb->update("tbl_bookings", $updateData);

    if (@$update) {
        $mydb->redirect("./?page=bookings");
    } else {
        $mydb->redirect("./?page=bookings&msg=dberror");
    }
}

if (@$_GET['action'] == 'delete') {
    $mydb->where("id", $id);
    $delete = $mydb->delete("tbl_bookings");
    if (@$delete) {
        $mydb->redirect("./?page=bookings&msg=deleted");
    } else {
        $mydb->redirect("./?page=bookings&msg=deleted-error");
    }
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Bookings</h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Bookings</li>
            <li class="active">List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Booking's List</h3>
                    </div>
                    <!-- /.box-header -->
                    <?php if (@$_GET['msg'] == 'dberror') { ?>
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
                        <table id="pages-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Client Name</th>
                                <th>Booked For</th>
                                <th>Package Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $result = $mydb->select("tbl_bookings");
                            $count = 1;
                            if ($mydb->totalRows > 0) {
                                foreach ($result as $row):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $count; ?>.</td>
                                        <td>
                                            <b><?= $row['name']; ?><?php echo($row['status'] == '1' ? '&nbsp;<span class="label label-success right-side">Unread</span></a>' : '&nbsp;<span class="label label-danger">Read</span></a>'); ?></b><br>
                                            <small><i><?= $row['email']; ?></i></small>
                                        </td>
                                        <td class="text-center"><?= date("F d, Y", strtotime($row['booked_at'])); ?></small></td>
                                        <td class="center"><?= $row['title']; ?>
                                            <?php

                                            switch ($row['package']):

                                                case 'tbl_peaks':
                                                    echo '<span class="label label-success right-side">Peaks</span>';
                                                    break;

                                                case 'tbl_expeditions';
                                                    echo '<span class="label label-default right-side">Expeditions</span>';
                                                    break;

                                                case 'tbl_trekkings';
                                                    echo '<span class="label label-waring right-side">Peaks</span>';
                                                    break;

                                                default;

                                            endswitch;

                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['status']) { ?><a
                                                href="./?page=bookings&action=status_change&id=<?= $row['id']; ?>"
                                                <button type="button" class="btn btn-success"><i
                                                        class="fa fa-envelope-o"></i> Mark as Read
                                                </button></a><?php } ?>
                                            <a href="#info" data-toggle="modal" data-id="<?= $row['id']; ?>"
                                               data-title="<?= $row['title']; ?>" data-name="<?= $row['name']; ?>"
                                               data-email="<?= $row['email']; ?>" data-phone="<?= $row['phone']; ?>"
                                               data-size="<?= $row['group_size']; ?>"
                                               data-season="<?= $row['season']; ?>"
                                               data-message="<?= $row['message']; ?>" class="info">
                                                <button type="button" class="btn btn-success"><i class="fa fa-eye"></i>
                                                    View
                                                </button>
                                            </a>
                                            <a href="#delete_branch" data-toggle="modal"
                                               data-id="<?= $row['id']; ?>" id="delete_branch<?= $row['id']; ?>"
                                               class="btn btn-danger" onClick="delete_branch(<?= $row['id']; ?>)"><i
                                                    class="fa fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                endforeach;
                            } else { ?>

                                <tr>
                                    <td colspan="5" class="text-center" style="color:#903;">No Bookings Listed</td>
                                </tr>

                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center" colspan="5"><?php echo C_NAME; ?> Bookings Table</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
</div>

<script type="text/javascript">

    function delete_branch(id) {
        var conn = './?page=bookings&action=delete&id='+id;
        $('#delete_branch a').attr("href", conn);
    }

    $(document).on("click", ".info", function () {

        var name = $(this).attr('data-name');
        var title = $(this).attr('data-title');
        var email = $(this).attr('data-email');
        var phone = $(this).attr('data-phone');
        var size = $(this).attr('data-size');
        var season = $(this).attr('data-season');
        var message = $(this).attr('data-message');


        $('#name').empty().html(name);
        $('#title').empty().html(title);
        $('#email').empty().html(email);
        $('#phone').empty().html(phone);
        $('#season').empty().html(season);
        $('#size').empty().html(size);
        $('#message').empty().html(message);
    });

</script>
<!-- DataTables -->
<script type="text/javascript">
    $(function () {
        $('#pages-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<div id="info" class="modal" style="max-height: 600px">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><i class="fa fa-info-circle"></i> Booking Info</h3>
    </div>
    <div class="modal-body">
        <h3 id="title"></h3>
        <p><b>Booked For:</b> <span id="season"></span></p>
        <p><b>Group Size:</b> <span id="size"></span></p>
        <p>&nbsp;</p>
        <p><b>Client's Name: </b><span id="name"></span></p>
        <p><b>Email:</b> <span id="email"></span></p>
        <p><b>Phone:</b> <span id="phone"></span></p>
        <p class="well"><i>"<span id="message"></span></i>"</p>
    </div>
    <div class="modal-footer"><a data-dismiss="modal" class="btn btn-danger" href="#">Cancel</a>
    </div>
</div>

<div id="delete_branch" class="modal">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3>Delete Booking</h3>
    </div>
    <div class="modal-body">
        <p>
            Do you want to delete this Booking Request?
        </p>
    </div>
    <div class="modal-footer"><a class="btn btn-primary" href="">Confirm</a> <a data-dismiss="modal"
                                                                                class="btn btn-danger" href="#">Cancel</a>
    </div>
</div>