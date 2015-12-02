<?php

import('store');
if(isset($_POST['shop_name'])) {
	$insert = array();
	if(!store_validate($insert)) {
		$error_msg = 'All fields must be filled';
	} else {
		$hanging_methods = $insert['hanging_methods'];
		unset($insert['hanging_methods']);
		$id = insert_to_db('stores', $insert);
		if($id) {
			$store_hanging_methods = array();
			foreach($hanging_methods as $value) {
				$store_hanging_methods[] = array('store_id'=>$id, 'hanging_method_id' => $value);
			}
			insert_multi_to_db('stores_hanging_methods', $store_hanging_methods);
			$_SESSION['edit_store_msg'] = 'Store Successfully Created';
			header('location: /store/edit/'.$id);
			die;
		}

	}
}
$hanging_methods = get_hanging_methods();
$template = set_template('store', 'index');
$link = THEME . 'template2.php';
require_once($link);