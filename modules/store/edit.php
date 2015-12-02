<?php
$url = explode('/', $_GET['url']);
if(!isset($url[2]) || !$id = abs((int)$url[2]))
	die('bad link');
$store = db_query_to_row("SELECT * FROM `stores` WHERE id = $id");
if(!$store){
	die('store not found');
}
import('store');
if(isset($_POST['shop_name'])) {
	$update = array();
	if(!store_validate($update)) {
		$error_msg = 'All fields must be filled';
	} else {
		$hanging_methods = $update['hanging_methods'];
		unset($update['hanging_methods']);
		if(update_in_db('stores', $update, '`id`='.$id)) {
			delete_from_db('stores_hanging_methods', '`store_id`='.$id);
			$store_hanging_methods = array();
			foreach($hanging_methods as $value) {
				$store_hanging_methods[] = array('store_id'=>$id, 'hanging_method_id' => $value);
			}
			insert_multi_to_db('stores_hanging_methods', $store_hanging_methods);
			$_SESSION['edit_store_msg'] = 'Store Successfully Updated';
			header('location: /store/edit/'.$id);
			die;
		}
	}
}
$hanging_methods = get_hanging_methods();
$store_hanging_methods = db_query_to_list('SELECT `hanging_method_id` FROM `stores_hanging_methods` WHERE `store_id`='.$id, 'hanging_method_id');
$template = set_template('store', 'edit');
$link = THEME . 'template2.php';
require_once($link);