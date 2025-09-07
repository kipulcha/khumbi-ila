<?php
$id = @$_GET['id'];
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Change Password&nbsp;&nbsp;
            <a href="./?page=user&id=<?=$id;?>"><button class="btn btn-primary"><i class="fa fa-user"></i> Profile</button></a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>User Setting</li>
            <li class="active">Change Password</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Form -->
        <form id="userForm" name="userFrom" enctype="multipart/form-data" action="">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title" id='page-title'>Change Password
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
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-11"><div class="input-group"><span class="input-group-addon"><i class="fa fa-key"></i>&nbsp;&nbsp;Password</span>
                                            <input type="password" class="form-control" placeholder="Password" name="password"
                                                   id="password" required style="width: 51%;"/>
                                        </div>
                                        <br>
                                    <div class="input-group"><span class="input-group-addon"><i class="fa fa-key"></i>&nbsp;&nbsp;Retype Password</span>
                                        <input type="password" class="form-control" placeholder="Password" name="repassword"
                                               id="repassword" style="width: 51%;" onKeyUp="pwd_check();" required/>
                                        <p id="pwd_text"></p>
                                    </div>
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
                    &nbsp;&nbsp;<a href="?page=user&id=<?=$id;?>" id="cancel" class="btn btn-danger"><i class="fa fa-remove"></i>
                        Cancel</a>
                </div>
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
                    window.location = './pages/user_function.php?action=update_pwd&uname=<?=$_SESSION['username'];?>&id=' + id + '&' + data;
                } else {
                    $('#sec_text').empty().html('Incorrect Password!');
                    $("#sec_check").css({
                        "background-color": "#F2DEDE",
                        "border-color": "#DD4B39"
                    });
                }
            },
            error: function(data) {
                alert('error');
            }
        });
    }
</script>