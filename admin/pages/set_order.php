<?php
require_once '../../config.php';

// get the list of items id separated by cama (,)
$list_order = $_POST['list_order'];
// convert the string list to an array
$list = explode(',' , $list_order);
$i = 1 ;
foreach($list as $id) {
	$updateData = array("item_order" => $i);
        $mydb->where("id", $id);
        $update = $mydb->update("tbl_packages", $updateData);

	/*try {
	    $sql  = 'UPDATE tbl_packages SET item_order = :item_order WHERE id = :id' ;
		$query = $pdo->prepare($sql);
		$query->bindParam(':item_order', $i, PDO::PARAM_INT);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
	} catch (PDOException $e) {
		echo 'PDOException : '.  $e->getMessage();
	}*/
	$i++ ;
}
$data = array('status'=> 'success');
		echo json_encode($data);
?>