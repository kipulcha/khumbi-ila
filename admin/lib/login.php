<?php

session_start();
require_once '../../config.php';

$username = trim( $_POST['username'] );
$password = trim( $_POST['password'] );

$dbpassword = $mydb->select_field("password", "tbl_admin", "username='" . $username . "'");

if (password_verify($password, $dbpassword)):

	$mydb->where("username", $username);
	$result =  $mydb->select("tbl_admin");

	if($mydb->totalRows > 0):

		foreach ($result as $row):
			$_SESSION['username']= $username;
			$_SESSION['userid'] = $row['id'];
			$_SESSION['userfullname'] = $row['name'];
			$_SESSION['user_group'] = $row['user_group'];
			$_SESSION['pwd'] = $row['password'];
		endforeach;

		echo 'yes';
	endif;

else:
	echo "notFound";

endif;

?>