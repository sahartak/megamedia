<?php
if(!isset($_SESSION['user'])){
	header('location: /');
	exit;
}
$type = get_url_param(2);

if(! $type_key = array_search($type, array(1=>'banners', 2=>'posters', 3=>'rollups'))) {
	header('location: /test/');
}

$weeks = array();
if(isset($_SESSION['order_id'])) {
	$order_id = $_SESSION['order_id'];
	$weeks = db_query_to_row('SELECT id, week_number FROM orders_weeks WHERE type = '.$type_key.' AND order_id = '.$order_id);
	if(!empty($weeks))
		$week_number = $weeks['week_number'];
}

if(isset($_POST['store']) && $count = count($_POST['store'])){

	// if not isset order in session then creating new order
	$order_id = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : insert_to_db('orders', array('user_id' => $_SESSION['user']['id']));

	$insert_array = array();

	//init order data for inserting to db
	for($i=0; $i<$count; $i++) {
		$insert = array();
		$insert['order_id'] = $order_id;
		$insert['type'] = $type_key;
		if($var = abs((int)$_POST['store'][$i]))
			$insert['store_id'] = $var;
		if($var = abs((int)$_POST['width'][$i]))
			$insert['width'] = $var;
		if($var = abs((int)$_POST['height'][$i]))
			$insert['height'] = $var;
		if($var = abs((int)$_POST['amount'][$i]))
			$insert['amount'] = $var;

		if($type_key == 1) {
			if($var = abs((int)$_POST['ophaeng'][$i]))
				$insert['ophaeng_id'] = $var;
			if($var = abs((int)$_POST['material'][$i]))
				$insert['material_id'] = $var;
			$valid_count = 8;
		} elseif($type_key == 2) {
			if($var = abs((int)$_POST['material'][$i]))
				$insert['material_id'] = $var;
			$valid_count = 7;
		} else {
			$valid_count = 6;
		}
		if(count($insert) == $valid_count)
			$insert_array[] = $insert;
	}

	//inserting order details to db
	insert_multi_to_db('additional_orders_items', $insert_array);

	$week_number = isset($_POST['week_number']) ? esc(htmlspecialchars(strip_tags(trim($_POST['week_number'])))) : 1;

	if(empty($weeks)) {
		insert_to_db('orders_weeks', array('order_id' => $order_id, 'week_number' => $week_number, 'type' => $type_key));
	} else {
		update_in_db('orders_weeks', array('week_number' => $week_number), 'id = '.$weeks['id']);
	}

	$_SESSION['order_id'] = $order_id;
	header('location: /test/checkout');
	exit;
}
import('store');
import('checkout');
$template_name = 'additional_'.$type;

// if user clicked last button getting last order details and showing last order template
if('last' == get_url_param(3)) {
	$additional_orders = get_last_additional_banner_order($_SESSION['user']['id'], $type_key);
	if($additional_orders)
		$template_name .= '_last';
	else {
		header('location: /test/additional/'.$type);
		exit;
	}
}

//setting view template
$template = set_template('test', $template_name);
$link = THEME . 'template2.php';
$stores = db_query_to_array("SELECT * FROM stores");

$stores_methods = array();
foreach($stores as $store) {
	$stores_methods[$store['id']] = get_store_hanging_methods($store['id']);
}
require_once($link);