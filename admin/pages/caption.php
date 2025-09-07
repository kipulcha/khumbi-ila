<?php

session_start();

include('../../config.php');

$tbl_name	= $_POST['caption_table'];
$caption	= $_POST['caption_text'];
$id			= $_POST['caption_id'];
$url		= $_POST['caption_url'];

$updateData = array("caption" => $caption);
$mydb->where("id", $id);
$update = $mydb->update("$tbl_name", $updateData);

if($update){
$mydb->redirect("../".$url."&msg=caption-added");
} else {
$mydb->redirect("../".$url."&msg=caption-error");	
}

?>