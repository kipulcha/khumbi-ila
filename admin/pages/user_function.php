<?php

require_once '../../config.php';

extract($_POST);

if($_GET['action'] == 'sec_check') {

    if (password_verify( $pwdCheck , $pwd )):
        echo 'true';
    endif;

} elseif($_GET['action'] == 'update') {

    $updateData = array("name" => trim($_GET['name']), "cell" => trim($_GET['phone']), "email" => trim($_GET['email']), "username" => trim($_GET['username']), "user_group" => trim($_GET['status']), "updated" => date('Y-m-d H:i:s'), "updated_by" => trim($_GET['uname']));
    $mydb->where("id", trim($_GET['id']));
    $update = $mydb->update("tbl_admin", $updateData);

    if (@$update):
        $mydb->redirect("../?page=user&id=" . $_GET['id'] . "&msg=updated");
    else:
        $mydb->redirect("../?page=user&id=" . $_GET['id'] . "&msg=dberror");
    endif;

} elseif($_GET['action'] == 'update_pwd') {

    $name = [
        'name' => trim($mydb->select_field("name", "tbl_admin", "id='" . $_GET['id'] . "'"))
    ];

    $password = trim($_GET['password']);

    $hash = password_hash($password, PASSWORD_BCRYPT, $name);

    $updateData = array("password" => $hash, "updated" => date('Y-m-d H:i:s'), "updated_by" => trim($_GET['uname']));
    $mydb->where("id", trim($_GET['id']));
    $update = $mydb->update("tbl_admin", $updateData);

    if (@$update):
        $mydb->redirect("../?page=user&id=" . $_GET['id'] . "&msg=updated");
    else:
        $mydb->redirect("../?page=user&id=" . $_GET['id'] . "&msg=dberror");
    endif;

} else{

    $mydb->redirect("../?page=404");

}
?>