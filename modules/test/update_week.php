<?php
if((!$order_id = $_SESSION['order_id']) || !isset($_POST['week']) || !isset($_POST['type']))
	die('no order');

$type = abs((int)$_POST['type']);
$week = htmlspecialchars(trim($_POST['week']));
if($week) {
	update_in_db('orders_weeks', array('week_number' => $week), "type = $type AND order_id = $order_id");
}
