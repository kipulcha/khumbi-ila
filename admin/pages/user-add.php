<?php

if ($_SESSION['user_group'] == 1) {
    $mydb->redirect("./?page=404");
}

if (isset($_POST['submit'])) {
    $name = [
        'name' => trim($_POST['name'])
    ];

    $password = trim($_POST['password']);

    $hash = password_hash($password, PASSWORD_BCRYPT, $name);

    $insertData = array("name" => trim($_POST['name']), "cell" => trim($_POST['phone']), "email" => trim($_POST['email']), "username" => trim($_POST['username']), "password" => $hash, "user_group" => trim($_POST['status']), "created" => date('Y-m-d H:i:s'), "created_by" => trim($_SESSION['username']), "updated" => "", "updated_by" => "");

    $insert = $mydb->insert("tbl_admin", $insertData);
    

    if (@$insert):
        $mydb->redirect("./?page=users&id=" . $_GET['id'] . "&msg=added");
    else:
        $mydb->redirect("./?page=users&id=" . $_GET['id'] . "&msg=dberror");
    endif;
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Add
            User&nbsp;&nbsp;<?php echo($_SESSION['user_group'] == 0 ? '<a href="./?page=users" class="btn btn-success" title="View User"><i class="fa fa-arrow-circle-left"></i> View Users</a>&nbsp;&nbsp;' : ''); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>User Setting</li>
            <li class="active">Add User</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Form -->
        <form id="userForm" name="userFrom" method="post" enctype="multipart/form-data" action="">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Add User</h3>
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
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i>&nbsp;&nbsp;Full Name</span>
                                    <input type="text" class="form-control" placeholder="Full Name" name="name"
                                           required/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i>&nbsp;&nbsp;Username</span>
                                    <input type="text" class="form-control" placeholder="Username" name="username"
                                           required/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-key"></i>&nbsp;&nbsp;Password</span>
                                    <input type="password" class="form-control" placeholder="Password" name="password"
                                           id="password" required style="width: 51%;"/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-key"></i>&nbsp;&nbsp;Retype Password</span>
                                    <input type="password" class="form-control" placeholder="Password" name="repassword"
                                           id="repassword" style="width: 51%;" onKeyUp="pwd_check();" required/>
                                    <p id="pwd_text"></p>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-users"></i>&nbsp;&nbsp;User Group</span>
                                    <select class="form-control" name="status" style="width: 50%;" required>
                                        <option value="1" selected>General User</option>
                                        <option value="0">Admin</option>
                                    </select>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i>&nbsp;&nbsp;Phone</span>
                                    <input type="text" class="form-control" placeholder="Phone" name="phone" required/>
                                </div>
                                <br>
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Email</span>
                                    <input type="email" class="form-control" placeholder="Email" name="email" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-floppy-o"></i> Add User
                    </button>
                    &nbsp;&nbsp;<a href="?page=users" id="cancel" class="btn btn-danger"><i class="fa fa-remove"></i>
                        Cancel</a></div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>


<script type="text/javascript">
    $('#userForm button[type=submit]').prop('disabled', true);

    //Password Check
    function pwd_check() {
        var pwd = $('#password').val();
        var pwd_check = $('#repassword').val();

        if (pwd == pwd_check) {
            $('#pwd_text').empty().html('Password Matches');
            $("#pwd_text").css("color", "green");
            $('#userForm button[type=submit]').prop('disabled', false);
        } else {
            $('#pwd_text').empty().html(' Password Does not Match');
            $("#pwd_text").css("color", "#DD4B39");
            $('#userForm button[type=submit]').prop('disabled', true);
        }

    }
</script>