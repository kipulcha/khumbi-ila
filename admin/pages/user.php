<?php
$id = @$_GET['id'];
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User
            Profile&nbsp;&nbsp;
            <?php echo($_SESSION['user_group'] == 0 ? '<a href="./?page=users" class="btn btn-success" title="View User"><i class="fa fa-arrow-circle-left"></i> View Users</a>' : ''); ?>
            <a href="./?page=user-change-password&id=<?=$id;?>"><button class="btn btn-primary"><i class="fa fa-key"></i> Change Password</button></a>
            <button class="btn btn-primary" id="edit" title="Edit Page"><i class="fa fa-edit"></i> Edit User</button>
            <button id="view" class="btn btn-info"><i class="fa fa-eye"></i> View User</button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>User Setting</li>
            <li class="active">Profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Form -->
        <form id="userForm" name="userFrom" enctype="multipart/form-data" action="">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>User Profile
                        : <?php echo $mydb->select_field("name", "tbl_admin", "id='" . $id . "'"); ?></h3>
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
                                <div class="alert alert-info alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button"><i
                                            class="fa fa-remove"></i></button>
                                    <h4>
                                        <i class="icon fa fa-check"></i>
                                        Error!
                                    </h4>
                                    Data could not be added
                                </div>
                            <?php } ?>
                            <div class="box-body">
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i>&nbsp;&nbsp;Full Name</span>
                                    <input type="text" class="form-control" placeholder="Full Name" name="name"
                                           value="<?php echo $mydb->select_field("name", "tbl_admin", "id='" . $id . "'"); ?>"/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i>&nbsp;&nbsp;Username</span>
                                    <input type="text" class="form-control" placeholder="Username" name="username"
                                           value="<?php echo $mydb->select_field("username", "tbl_admin", "id='" . $id . "'"); ?>"/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-users"></i>&nbsp;&nbsp;User Group</span>
                                    <select class="form-control" name="status" style="width: 50%;">
                                        <?php
                                        $status = $mydb->select_field("user_group", "tbl_admin", "id='" . $id . "'");;
                                        if ($status == 1) {
                                            ?>
                                            <option value="1" selected>General User</option>
                                            <option value="0">Admin</option>
                                        <?php } else { ?>
                                            <option value="0" selected>Admin</option>
                                            <option value="1">General User</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i>&nbsp;&nbsp;Phone</span>
                                    <input type="text" class="form-control" placeholder="Phone" name="phone"
                                           value="<?php echo $mydb->select_field("cell", "tbl_admin", "id='" . $id . "'"); ?>"/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Email</span>
                                    <input type="text" class="form-control" placeholder="Email" name="email"
                                           value="<?php echo $mydb->select_field("email", "tbl_admin", "id='" . $id . "'"); ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" href="#security_check" data-toggle="modal" class="btn btn-primary"
                            name="submit"><i class="fa fa-floppy-o"></i> Save Changes
                    </button>
                    &nbsp;&nbsp;<a href="?page=users" id="cancel" class="btn btn-danger"><i class="fa fa-remove"></i>
                        Cancel</a></div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>

<!--Security Check Modal-->
<div id="security_check" class="modal">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><i class="fa fa-lock"></i> Security Check</h3>
    </div>
    <div class="modal-body">
        <p id="sec_text">Please enter your password:</p>
        <input type="password" class="form-control" placeholder="Password" name="sec_check" id="sec_check"/>
    </div>
    <div class="modal-footer"><a class="btn btn-primary" id="pwd_txt" data-password="<?php echo $_SESSION['pwd']; ?>"
                                 onClick="security_check(<?= $id; ?>)">Confirm</a> <a data-dismiss="modal"
                                                                                      class="btn btn-danger" href="#">Cancel</a>
    </div>
</div>
<!--//Security Check Modal-->


<script type="text/javascript">
    $(document).ready(function () {
        $('#userForm input').prop('readonly', true);
        $('#userForm select').prop('disabled', true);
        $('#userForm button[type=submit]').hide();
        $('#view').hide();
        $('#cancel').hide();
    });

    $("#edit").click(function () {
        $('#userForm input').prop('readonly', false);
        $('#userForm select').prop('disabled', false);
        $('#userForm button[type=submit]').show();
        $('#page-title').html("Edit User : <?php echo $mydb->select_field("name", "tbl_admin", "id='" . $id . "'"); ?>");
        $('#edit').hide();
        $('#view').show();
        $('#cancel').show();
    });

    $("#view").click(function () {
        $('#userForm input').prop('readonly', true);
        $('#userForm select').prop('disabled', true);
        $('#userForm button[type=submit]').hide();
        $('#page-title').html("User Profile : <?php echo $mydb->select_field("name", "tbl_admin", "id='" . $id . "'"); ?>");
        $('#view').hide();
        $('#edit').show();
        $('#cancel').hide();
    });

    //Security Check
    function security_check(id) {
        $('#sec_text').empty();
        $("#sec_check").css({
            "background-color": "#fff",
            "border-color": "#e5e5e5"
        });
        var pwd = $('#pwd_txt').attr('data-password');
        var pwdCheck = $('#sec_check').val();
        $.ajax({
            url: './pages/user_function.php?action=sec_check',
            type: 'POST',
            data: {
                pwd : pwd,
                pwdCheck : pwdCheck
            },
            success: function(result) {

                if (result == 'true') {
                    var data = $('form').serialize();
                    window.location = './pages/user_function.php?action=update&uname=<?=$_SESSION['username'];?>&id=' + id + '&' + data;
                } else {
                    $('#sec_text').empty().html('Incorrect Password!');
                    $("#sec_check").css({
                        "background-color": "#F2DEDE",
                        "border-color": "#DD4B39"
                    });
                }
            }
        });
    }
</script>