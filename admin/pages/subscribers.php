<?php

$id = @$_GET['id'];

if (@$_GET['action'] == 'status_change') {

    $statusOld = $mydb->select_field("status", "tbl_subscribers", "id='" . $id . "'");

    if($statusOld == '1'):
        $statusNew = '0';
    elseif ($statusOld == '0'):
        $statusNew = '1';
    endif;

    $updateData = array("status" => $statusNew);
    $mydb->where("id", $id);
    $update = $mydb->update("tbl_subscribers", $updateData);

    if (@$update) {
        $mydb->redirect("./?page=subscribers");
    } else {
        $mydb->redirect("./?page=subscribers&msg=dberror");
    }
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Subscribers</h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Subscribers</li>
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
                        <h3 class="box-title">Subscriber's List</h3>
                    </div>
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
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="pages-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Email</th>
                                <th>Subscribed On</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $result =  $mydb->select("tbl_subscribers");
                            $count = 1;
                            if($mydb->totalRows > 0){
                                foreach ($result as $row):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?=$count;?>.</td>
                                        <td><b><?=$row['email'];?><?php echo ($row['status'] == '1' ? '&nbsp;<span class="label label-success">Active</span></a>' : '&nbsp;<span class="label label-danger">Inactive</span></a>' );?></b></td>
                                        <td class="text-center"><?=date("F d, Y", strtotime($row['created_at']));?></small></td>
                                        <td class="text-center">
                                            <a href="./?page=subscribers&action=status_change&id=<?=$row['id'];?>" <button type="button" class="btn btn-success"><i class="fa fa-refresh"></i> Change Status</button></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                endforeach;
                            } else { ?>

                                <tr>
                                    <td colspan="4" class="text-center" style="color:#903;">No Subscribers Listed</td>
                                </tr>

                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center" colspan="4"><?php echo C_NAME; ?> Subscribers Table</th>
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
