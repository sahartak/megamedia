<?php
if(!isset($_SESSION['user'])){
	header('location: /');
	exit;
}
import('store');
import('checkout');

$weeks = array();
if(isset($_SESSION['order_id'])) {
	$order_id = $_SESSION['order_id'];
	$weeks = db_query_to_row('SELECT id, week_number FROM orders_weeks WHERE type = 0 AND order_id = '.$order_id);
	if(!empty($weeks))
		$week_number = $weeks['week_number'];
}

if(!empty($_POST)) {

	$inserted = false;

	if(isset($_POST['banners']) && is_array($_POST['banners']) && !empty($_POST['banners'])) {
		$banners = &$_POST['banners'];
		// loading post data for banners and validating
		clear_campaign_data($banners, 3);
		if(!empty($banners)) {
			if(!isset($order_id)) {
				$order_id = insert_to_db('orders', array('user_id' => $_SESSION['user']['id']));
			}
			//inserting banners order details to db
			insert_campaign_data($banners, $order_id, 1);
			$inserted = true;
		}
	}

	if(isset($_POST['posters']) && is_array($_POST['posters']) && !empty($_POST['posters'])) {
		$posters = &$_POST['posters'];
		// loading post data for posters and validating
		clear_campaign_data($posters, 2);
		if(!empty($posters)) {
			if(!isset($order_id)) {
				$order_id = insert_to_db('orders', array('user_id' => $_SESSION['user']['id']));
			}
			//inserting posters order details to db
			insert_campaign_data($posters, $order_id, 2);
			$inserted = true;
		}
	}

	if(isset($_POST['rollups']) && is_array($_POST['rollups']) && !empty($_POST['rollups'])) {
		$rollups = &$_POST['rollups'];
		clear_campaign_data($rollups, 1);
		if(!empty($rollups)) {
			if(!isset($order_id)) {
				$order_id = insert_to_db('orders', array('user_id' => $_SESSION['user']['id']));
			}
			insert_campaign_data($rollups, $order_id, 3);
			$inserted = true;
		}
	}

	if($inserted) {
		$week_number = isset($_POST['week_number']) ? esc(htmlspecialchars(strip_tags(trim($_POST['week_number'])))) : 1;

		if(empty($weeks)) {
			insert_to_db('orders_weeks', array('order_id' => $order_id, 'week_number' => $week_number));
		} else {
			update_in_db('orders_weeks', array('week_number' => $week_number), 'id = '.$weeks['id']);
		}
	}

	if(isset($order_id)){
		$_SESSION['order_id'] = $order_id;
		header('location: /test/checkout');
		exit;
	}
}

$template_name = 'add';
$url = explode('/', $_GET['url']);
if(isset($url[2]) && $url[2] == 'last') {
	$weeks = array();
	$campaign_orders = get_campaign_last_order($_SESSION['user']['id']);
	if($campaign_orders)
		$template_name = 'add_last';
	else {
		header('location: /test/add');
		exit;
	}
}


$template = set_template('test', $template_name);
$link = THEME . 'template2.php';

$user_id  = $_SESSION['user']['id'];

$stores = db_query_to_array("SELECT * FROM stores");

$stores_methods = array();
foreach($stores as $store) {
	$stores_methods[$store['id']] = get_store_hanging_methods($store['id']);
}
require_once($link);